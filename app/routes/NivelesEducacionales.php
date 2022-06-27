<?php

//* Listado de niveles educacionales del sistema
$app->get("/nivelesEducacionales/idioma/{idioma}", function ($request, $response, $args) {

    $idioma = $args['idioma'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/nivelesEducacionales/" . $idioma
        ),
        'data' => array()
    );

    if ($conn !== null) {

        //Buscar estados civiles
        $object = new NivelEducacional();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            if ($idioma === "es") {
                $nombre = $value['nombre_es'];
            } else {
                $nombre = $value['nombre_en'];
            }

            $payload['data'][] = array(
                'type' => 'nivelesEducacionales',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $nombre
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
