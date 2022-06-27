<?php

//* Listado de tipos de entrevistas del sistema
$app->get("/tiposEntrevistas/idioma/{idioma}", function ($request, $response, $args) {

    $idioma = $args['idioma'];

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/tiposEntrevistas/" . $idioma
        ),
        'data' => array()
    );

    if ($conn !== null) {

        //Buscar tipos de entrevista
        $object = new TipoEntrevista();

        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            if ($idioma === "es") {
                $nombre_idioma = $value['nombre_es'];
            } else {
                $nombre_idioma = $value['nombre_en'];
            }

            $payload['data'][] = array(
                'type' => 'tiposEntrevistas',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $nombre_idioma
                )
            );
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    //Encodear resultado
    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();
    return $response;
})->add(new JwtMiddleware());
