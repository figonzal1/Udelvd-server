<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

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
require_once("../app/class/Entrevistado.php");
require_once("../app/class/Profesion.php");
require_once("../app/class/Ciudad.php");
require_once("../app/class/EstadoCivil.php");
require_once("../app/class/NivelEducacional.php");
require_once("../app/class/TipoConvivencia.php");
require_once("../app/class/Profesion.php");
require_once("../app/class/TipoEntrevista.php");

/**
 * UTILS
 */
require_once("../app/utils/Jwt.php");
require_once("../app/utils/DynamicLink.php");
require_once("../app/utils/Mail.php");
require_once("../app/utils/Notificacion.php");

/**
 * MIDDLEWARE
 */
require_once("../app/middleware/JwtMiddleware.php");

/**
 * UTILS
 */
require_once("../app/utils/ErrorJsonHandler.php");

date_default_timezone_set('America/Santiago');
header('Cache-Control: no-cache');
header('X-Content-Type-Options: nosniff');
header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
header('X-Frame-Options: DENY');
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: frame-ancestors 'none'; default-src 'none'; script-src 'none'; connect-src 'none'; img-src 'none'; style-src 'self';frame-src 'none';");
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

/**
 * DEV MODE
 */
//$errorMiddleware = $app->addErrorMiddleware(true, true, true);

/**
 * PROD MODE
 */
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

/**
 * RUTAS
 */
require("../app/routes/Home.php");
require("../app/routes/Acciones.php");
require("../app/routes/Emoticones.php");
require("../app/routes/Entrevistas.php");
require("../app/routes/Eventos.php");
require("../app/routes/Investigadores.php");
require("../app/routes/Entrevistados.php");
require("../app/routes/Ciudades.php");
require("../app/routes/EstadosCiviles.php");
require("../app/routes/NivelesEducacionales.php");
require("../app/routes/TiposConvivencias.php");
require("../app/routes/Profesiones.php");
require("../app/routes/TiposEntrevistas.php");

$app->run();
