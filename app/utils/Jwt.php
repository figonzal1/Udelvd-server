<?php

#require '../../vendor/autoload.php';
date_default_timezone_set('America/Santiago');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Clase para manejar Tokens JWT
 */
class Jwt
{

    function __construct()
    {
        $dotenv = Dotenv\Dotenv::create(__DIR__ . "../../../");
        $dotenv->load();
    }

    public function generarToken($id_investigador)
    {
        $signer = new Sha256();

        $time = time();
        $expiration_date = strtotime("next sunday 01:00");  //Sunday 01:00 AM
        $token = (new Builder())
            ->issuedBy('https://undiaenlavidade.cl') // Configures the issuer (iss claim)
            ->permittedFor('android') // Configures the audience (aud claim)
            ->identifiedBy(getenv("JWT_JTI")) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time - 1) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($expiration_date) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $id_investigador) // Configures a new claim, called "uid"
            ->getToken($signer, new Key(getenv("HMAC_KEY")));
        //->getToken($signer, new ); // Retrieves the generated token

        //$token->getHeaders(); // Retrieves the token headers
        //$token->getClaims(); // Retrieves the token claims

        //echo $token->getHeader('jti'); // will print "4f1g23a12aa"
        //echo $token->getClaim('iss'); // will print "http://example.com"
        //echo $token->getClaim('uid'); // will print "1"
        //echo $token;

        return $token->__toString();
    }

    /**
     * Verifica validez de token (PAYLOAD)
     */
    public function validarToken($autorization_header)
    {

        $string_token = $this->getBearerToken($autorization_header[0]);

        //Parseo de token a object
        try {
            $token = (new Parser())->parse((string) $string_token);
        } catch (Exception $e) {
            error_log("Fail to parse token " . $e->getMessage(), 0);
            return false;
        }

        //Si el token no es verificable 
        if (!$this->verificarToken($token)) {
            return false;
        }

        //Si el token es veridico
        else {

            //Datos para valicacion
            $data = new ValidationData();
            $data->setIssuer("https://undiaenlavidade.cl");
            $data->setAudience("android");
            $data->setId(getenv("JWT_JTI"));

            try {
                $status = $token->validate($data);
            } catch (Exception $e) {
                //echo "Fail to validate token" . $e->getMessage();
                error_log("Fail to validate token" . $e->getMessage(), 0);
            }

            return $status;
        }
    }

    /**
     * Verifica que JWT no ha sido modificado (SHA256)
     */
    private function verificarToken($token)
    {
        try {
            $signer = new Sha256();
            //$status = $token->verify($signer, 'Test');
            $status = $token->verify($signer, new Key(getenv("HMAC_KEY")));
        } catch (Exception $e) {
            error_log("Fail to verify token " . $e->getMessage(), 0);
        }
        return $status;
    }

    /**
     * Obtener token desde bearer
     */
    private function getBearerToken($autorization_header)
    {
        // HEADER: Get the access token from the header
        if (!empty($autorization_header)) {
            if (preg_match('/Bearer\s(\S+)/', $autorization_header, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
