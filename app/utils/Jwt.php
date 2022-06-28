<?php /** @noinspection ForgottenDebugOutputInspection */

date_default_timezone_set('America/Santiago');

use Dotenv\Dotenv;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

/**
 * Clase para manejar Tokens JWT
 */
class Jwt
{

    private Configuration $configuration;

    public function __construct()
    {

        $dotenv = Dotenv::createImmutable(__DIR__ . "../../../");
        $dotenv->load();

        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($_ENV["JWT_ENCODER"])
        );

        //Set validations
        $this->configuration->setValidationConstraints(
            new IssuedBy('https://undiaenlavidade.cl'),
            new PermittedFor("android"),
            new IdentifiedBy($_ENV['JWT_JTI']),
            new SignedWith($this->configuration->signer(), $this->configuration->signingKey()),
            new StrictValidAt(new SystemClock(new DateTimeZone('America/Santiago')))
        );
    }

    public function generarToken($id_investigador): string
    {

        $now = new DateTimeImmutable();
        $token = $this->configuration->builder()
            ->issuedBy('https://undiaenlavidade.cl') // Configures the issuer (iss claim)
            ->permittedFor('android') // Configures the audience (aud claim)
            ->identifiedBy($_ENV["JWT_JTI"]) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($now) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($now->modify("-1 minute")) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($now->modify("+7 days")) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $id_investigador) // Configures a new claim, called "uid"
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }

    /**
     * Verifica validez de token (PAYLOAD)
     */
    public function validarToken($autorizationHeader): bool
    {

        $string_token = $this->getBearerToken($autorizationHeader[0]);
        $parsedToken = $this->configuration->parser()->parse($string_token);
        $constraints = $this->configuration->validationConstraints();

        if (!$this->configuration->validator()->validate($parsedToken, ...$constraints)) {
            error_log("Fail to validate token");
            return false;
        }
        return true;
    }

    /**
     * Obtener token desde bearer
     */
    private function getBearerToken($autorizationHeader)
    {
        // HEADER: Get the access token from the header
        if (!empty($autorizationHeader) && preg_match('/Bearer\s(\S+)/', $autorizationHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
