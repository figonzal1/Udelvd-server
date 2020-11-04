<?php

//* Agregar evento
$app->post('/entrevistas/{id_entrevista}/eventos', function ($request, $response, $args) {

    $id_entrevista = $args['id_entrevista'];

    $payload = array(
        'links' => array(
            'self' => '/entrevistas/' . $id_entrevista . "/eventos"
        )
    );

    //Obtener parametros post
    $data = $request->getParsedBody();

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!isset($id_entrevista) || empty($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_accion']) || empty($data['id_accion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_accion is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_emoticon']) || empty($data['id_emoticon'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_emoticon is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['justificacion']) || empty($data['justificacion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Justificacion is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['hora_evento']) || empty($data['hora_evento'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Hora_evento is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar eventos
        $object = new Evento();
        $object->setIdEntrevista($id_entrevista);
        $object->setIdAccion($data['id_accion']);
        $object->setIdEmoticon($data['id_emoticon']);
        $object->setJustificacion($data['justificacion']);
        $object->setHoraEvento($data['hora_evento']);

        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastid);
            $evento = $object->buscarEvento($conn);

            //Formatar respuesta
            $payload['data'] = array(
                'type' => 'eventos',
                'id' => $evento['id'],
                'attributes' => array(
                    'id_entrevista' => $evento['id_entrevista'],
                    'id_accion' => $evento['id_accion'],
                    'id_emoticon' => $evento['id_emoticon'],
                    'justificacion' => $evento['justificacion'],
                    'hora_evento' => $evento['hora_evento'],
                    'create_time' => $evento['create_time']
                )
            );

            $response = $response->withStatus(201);
        }
    }

    //Connection error
    else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Obtener evento especifico de una entrevista
$app->get('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $id_entrevista = $args['id_entrevista'];
    $id_evento = $args['id_evento'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $id_entrevista . "/eventos" . $id_evento
        ),
        'data' => array()
    );
    if (!isset($id_entrevista) || empty($id_entrevista) || !is_numeric($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    }
    if (!isset($id_evento) || empty($id_evento) || !is_numeric($id_evento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar eventos de entrevista
        $object = new Evento();
        $object->setIdEntrevista($id_entrevista);
        $object->setId($id_evento);

        $evento = $object->buscarEvento($conn);

        //Si usuario no existe
        if (empty($evento)) {
            $payload['data'] = array();
        } else {

            //Preparar respuesta
            $payload['data'] =
                array(
                    'type' => 'eventos',
                    'id' => $evento['id'],
                    'attributes' => array(
                        'id_entrevista' => $evento['id_entrevista'],
                        'id_accion' => $evento['id_accion'],
                        'id_emoticon' => $evento['id_emoticon'],
                        'justificacion' => $evento['justificacion'],
                        'hora_evento' => $evento['hora_evento']

                    )
                );
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Editar un evento
$app->put('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $id_entrevista = $args['id_entrevista'];
    $id_evento = $args['id_evento'];

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $id_entrevista . "/eventos/" . $id_evento
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $putdata = array();
    parse_str($data, $putdata);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * VALIDACION PARAMETROS
     */
    if (!is_numeric($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if (!is_numeric($id_evento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_accion']) || empty($putdata['id_accion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_accion is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_emoticon']) || empty($putdata['id_emoticon'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_emoticon is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['justificacion']) || empty($putdata['justificacion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Justificacion is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['hora_evento']) || empty($putdata['hora_evento'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Hora_evento is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Actualizar evento
        $object  = new Evento();
        $object->setId($id_evento);
        $object->setIdEntrevista($id_entrevista);
        $object->setJustificacion($putdata['justificacion']);
        $object->setIdAccion($putdata['id_accion']);
        $object->setIdEmoticon($putdata['id_emoticon']);
        $object->setHoraEvento($putdata['hora_evento']);

        $actualizar = $object->actualizar($conn);

        if (!$actualizar) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $evento = $object->buscarEvento($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'eventos',
                'id' => $evento['id'],
                'attributes' => array(
                    'id_entrevista' => $evento['id_entrevista'],
                    'id_accion' => $evento['id_accion'],
                    'id_emoticon' => $evento['id_emoticon'],
                    'justificacion' => $evento['justificacion'],
                    'hora_evento' => $evento['hora_evento'],
                    'update_time' => $evento['update_time']
                )
            );

            $response = $response->withStatus(201);
        }
    }

    //Connection error
    else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Eliminar un evento de entrevista
$app->delete('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $id_entrevista = $args['id_entrevista'];
    $id_evento = $args['id_evento'];

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $id_entrevista . "/eventos/" . $id_evento
        )
    );
    if (!isset($id_entrevista) || empty($id_entrevista) || !is_numeric($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($id_evento) || empty($id_evento) || !is_numeric($id_evento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Eliminar eventos
        $object = new Evento();
        $object->setId($id_evento);
        $object->setIdEntrevista($id_entrevista);

        $eliminar = $object->eliminar($conn);

        if ($eliminar) {

            $response = $response->withStatus(200);
            $payload['data'] = array();
        } else {
            $payload = ErrorJsonHandler::lanzarError($payload, 404, 'Delete problem', 'Delete object has fail');
            $response = $response->withStatus(404);
        }
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Obtener eventos de una entrevista
$app->get('/entrevistas/{id_entrevista}/eventos/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    $id_entrevista = $args['id_entrevista'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $id_entrevista . "/eventos/" . $idioma
        ),
        'data' => array()
    );
    if (!isset($id_entrevista) || empty($id_entrevista) || !is_numeric($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar eventos de entrevista
        $object = new Evento();
        $object->setIdEntrevista($id_entrevista);

        $listado = $object->buscarEventosEntrevista($conn, $idioma);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'eventos',
                    'id' => $value['id'],
                    'attributes' => array(
                        'id_entrevista' => $value['id_entrevista'],
                        'id_accion' => $value['id_accion'],
                        'id_emoticon' => $value['id_emoticon'],
                        'justificacion' => $value['justificacion'],
                        'hora_evento' => $value['hora_evento']
                    ),
                    'relationships' => array(
                        'accion' => array(
                            'data' => array(
                                'id' => $value['id_accion_a'],
                                'nombre' => $value['nombre_accion']
                            )
                        ),
                        'emoticon' => array(
                            'data' => array(
                                'id' => $value['id_emoticon_e'],
                                'url' => $value['url_emoticon'],
                                'descripcion' => $value['descripcion_emoticon']
                            )
                        )
                    )
                )
            );
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());
