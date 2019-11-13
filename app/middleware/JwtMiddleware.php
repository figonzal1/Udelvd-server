<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once("app/class/Jwt.php");

/**
 * Clase Middleware encargada de manejar logica de JWT
 */
class JwtMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);

        //Obtener token JWT
        $authorization_header = $request->getHeader("Authorization");

        $jwt = new Jwt();

        /**
         * Procesar token vacio
         */
        if (!isset($authorization_header) || empty($authorization_header)) {

            $payload = [];
            $payload = ErrorJsonHandler::lanzarError($payload, 403, 'Auth problem', 'Token auth is empty');
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            $response = new Response();
            $response->getBody()->write($payload);
            $response->withStatus(403);
            return $response;
        } 
        
        /**
         * Procesar token invalido
         */
        else if (!$jwt->validarToken($authorization_header)) {
            $payload = [];
            $payload = ErrorJsonHandler::lanzarError($payload, 403, 'Auth problem', 'Token invalid');
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            $response = new Response();
            $response->getBody()->write($payload);
            $response->withStatus(403);
            return $response;
        } 
        
        /**
         * Procesar token valido
         */
        else if ($jwt->validarToken($authorization_header)) {
            return $response;
        }
    }
}
