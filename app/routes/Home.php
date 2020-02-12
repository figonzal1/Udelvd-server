<?php

use Slim\Views\PhpRenderer;
$app->get('/', function ($request, $response, $args) {

    $renderer = new PhpRenderer('../app/templates');
    $response = $response->withHeader('Content-type', 'text/html; charset=utf-8');
    return $renderer->render($response, "home.html", $args);
});
