<?php /** @noinspection ForgottenDebugOutputInspection */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Clase Middleware encargada de manejar logica de JWT
 */
class JwtMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response|ResponseInterface
    {
        $response = $handler->handle($request);

        //Obtener token JWT
        $authorization_header = $request->getHeader("Authorization");

        $jwt = new Jwt();

        /**
         * Procesar token vacio
         */
        if (empty($authorization_header)) {

            $payload = [];
            $payload = ErrorJsonHandler::lanzarError($payload, 403, 'Auth problem', 'Token auth is empty');

            try {
                $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            } catch (JsonException $e) {
                error_log("JSON Exception: " . $e->getMessage());
            }

            $response = new Response();
            $response->getBody()->write($payload);
            return $response->withStatus(403);

        }

        if ($jwt->validarToken($authorization_header)) {
            return $response;
        } else {
            $payload = [];
            $payload = ErrorJsonHandler::lanzarError($payload, 403, 'Auth problem', 'Token invalid');

            try {
                $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            } catch (JsonException $e) {
                error_log("JSON Exception: " . $e->getMessage());
            }

            $response = new Response();
            $response->getBody()->write($payload);
            return $response->withStatus(403);
        }
    }
}
