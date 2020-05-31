<?php

//* Listado de visualizaciones de estadisticas en el sistema
$app->get('/estadisticas', function ($request, $response, $args) {

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/estadisticas"
        ),
        'data' => array()
    );

    if ($conn != null) {
        //Buscar estadisticas
        $object = new Estadisticas();
        $listado = $object->buscarEstadisticas($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'estadisticas',
                    'id' => $value['id'],
                    'attributes' => array(
                        'url' => $value['url'],
                        'nombre_es' => $value['nombre_es'],
                        'nombre_en' => $value['nombre_en']
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
