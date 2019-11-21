<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

/**
 * Clase para manejar Tokens JWT
 */
class Jwt
{

    public function generarToken($id_investigador)
    {

        $signer = new Sha256();
        $time = time();
        $token = (new Builder())
            ->issuedBy('http://udelvd.cl') // Configures the issuer (iss claim)
            ->permittedFor('android') // Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time - 1) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 60) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $id_investigador) // Configures a new claim, called "uid"
            ->getToken($signer, new Key('Felipe')); // Retrieves the generated token


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
            $data->setIssuer("http://udelvd.cl");
            $data->setAudience("android");
            $data->setId('4f1g23a12aa');

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
            $status = $token->verify($signer, 'Felipe');
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
