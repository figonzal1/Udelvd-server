<?php

$app->get('/', function ($request, $response, $args) {

    $response->getBody()->write(
        "API del proyecto del App - Un día en la vida de ...\nAutor: Felipe González");
    return $response;
});
