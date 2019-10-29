<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

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

require 'app/routes/Investigadores.php';

$app ->run();
?>