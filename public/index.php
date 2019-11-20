<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;

require '../vendor/autoload.php';

/**
 * CONFIGS
 */
require_once("../app/config/MysqlAdapter.php");

/**
 * CLASES
 */
require_once("../app/class/Accion.php");
require_once("../app/class/Emoticon.php");
require_once("../app/class/Entrevista.php");
require_once("../app/class/Evento.php");
require_once("../app/class/Investigador.php");
require_once("../app/class/Jwt.php");
require_once("../app/class/Usuario.php");

/**
 * MIDDLEWARE
 */
require_once("../app/middleware/JwtMiddleware.php");

/**
 * UTILS
 */
require_once("../app/utils/ErrorJsonHandler.php");

header('Cache-Control: no-cache');
header('X-Content-Type-Options: nosniff');
header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
header('X-Frame-Options: DENY');
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'none'; script-src 'none'; connect-src 'none'; img-src 'none'; style-src 'none';");
header("Referrer-Policy: no-referrer");
header("Feature-Policy: camera 'none'; fullscreen 'none'; geolocation 'none'; microphone 'none';");

$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

/*
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, false, false);

/**
 * RUTAS
 */
require("../app/routes/Acciones.php");
require("../app/routes/Emoticones.php");
require("../app/routes/Entrevistas.php");
require("../app/routes/Evento.php");
require("../app/routes/Investigadores.php");
require("../app/routes/Usuarios.php");

$app->run();
