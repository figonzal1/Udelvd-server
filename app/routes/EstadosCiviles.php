<?php

//* Listado de estados civiles del sistema
$app->get("/estadosCiviles/idioma/{idioma}", function ($request, $response, $args) {

    $idioma = $args['idioma'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/estadosCiviles/" . $idioma
        ),
        'data' => array()
    );

    if ($conn != null) {

        //Buscar estados civiles
        $object = new EstadoCivil();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            if ($idioma == "es") {
                $nombre = $value['nombre_es'];
            } else if ($idioma == "en") {
                $nombre = $value['nombre_en'];
            }

            array_push(
                $payload['data'],
                array(
                    'type' => 'estadosCiviles',
                    'id' => $value['id'],
                    'attributes' => array(
                        'nombre' => $nombre
                    )
                )
            );
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    //Encodear resultado
    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();
    return $response;
})->add(new JwtMiddleware());
