<?php

/**
 * GET /tiposEntrevistas: Listado de tipos de entrevistas del sistema
 */
$app->get("/tiposEntrevistas[/]",function($request,$response,$args){

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/tiposEntrevistas"
        ),
        'data' => array()
    );

    if ($conn != null) {

        //Buscar tipos de entrevista
        $object = new TipoEntrevista();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'tiposEntrevistas',
                    'id' => $value['id'],
                    'attributes' => array(
                        'nombre' => $value['nombre']
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
});