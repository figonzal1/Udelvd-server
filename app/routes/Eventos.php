<?php

//* Agregar evento
$app->post('/entrevistas/{id_entrevista}/eventos', function ($request, $response, $args) {

    $idEntrevista = $args['id_entrevista'];

    $payload = array(
        'links' => array(
            'self' => '/entrevistas/' . $idEntrevista . "/eventos"
        )
    );

    //Obtener parametros post
    $data = $request->getParsedBody();

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (empty($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['id_accion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_accion is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['id_emoticon'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_emoticon is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['justificacion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Justificacion is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['hora_evento'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Hora_evento is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar eventos
        $object = new Evento();
        $object->setIdEntrevista($idEntrevista);
        $object->setIdAccion($data['id_accion']);
        $object->setIdEmoticon($data['id_emoticon']);
        $object->setJustificacion($data['justificacion']);
        $object->setHoraEvento($data['hora_evento']);

        $lastId = $object->agregar($conn);

        //Insert error
        if (!$lastId) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastId);
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
    } //Connection error
    else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Obtener evento especifico de una entrevista
$app->get('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $idEntrevista = $args['id_entrevista'];
    $idEvento = $args['id_evento'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $idEntrevista . "/eventos" . $idEvento
        ),
        'data' => array()
    );
    if (empty($idEntrevista) || !is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    }
    if (empty($idEvento) || !is_numeric($idEvento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar eventos de entrevista
        $object = new Evento();
        $object->setIdEntrevista($idEntrevista);
        $object->setId($idEvento);

        $evento = $object->buscarEvento($conn);

        //Si usuario no existe
        if (empty($evento)) {
            $payload['data'] = array();
        } else {

            //Preparar respuesta
            $payload['data'] = array(
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

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Editar un evento
$app->put('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $idEntrevista = $args['id_entrevista'];
    $idEvento = $args['id_evento'];

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $idEntrevista . "/eventos/" . $idEvento
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $putData = array();
    parse_str($data, $putData);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * VALIDACION PARAMETROS
     */
    if (!is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if (!is_numeric($idEvento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if (empty($putData['id_accion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_accion is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['id_emoticon'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_emoticon is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['justificacion'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Justificacion is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['hora_evento'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Hora_evento is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Actualizar evento
        $object = new Evento();
        $object->setId($idEvento);
        $object->setIdEntrevista($idEntrevista);
        $object->setJustificacion($putData['justificacion']);
        $object->setIdAccion($putData['id_accion']);
        $object->setIdEmoticon($putData['id_emoticon']);
        $object->setHoraEvento($putData['hora_evento']);

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
    } //Connection error
    else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Eliminar un evento de entrevista
$app->delete('/entrevistas/{id_entrevista}/eventos/{id_evento}', function ($request, $response, $args) {

    $idEntrevista = $args['id_entrevista'];
    $idEvento = $args['id_evento'];

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $idEntrevista . "/eventos/" . $idEvento
        )
    );
    if (empty($idEntrevista) || !is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if (empty($idEvento) || !is_numeric($idEvento)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_evento must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Eliminar eventos
        $object = new Evento();
        $object->setId($idEvento);
        $object->setIdEntrevista($idEntrevista);

        $eliminar = $object->eliminar($conn);

        if ($eliminar) {

            $response = $response->withStatus(200);
            $payload['data'] = array();
        } else {
            $payload = ErrorJsonHandler::lanzarError($payload, 404, 'Delete problem', 'Delete object has fail');
            $response = $response->withStatus(404);
        }
    }

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());

//* Obtener eventos de una entrevista
$app->get('/entrevistas/{id_entrevista}/eventos/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    $idEntrevista = $args['id_entrevista'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistas/" . $idEntrevista . "/eventos/" . $idioma
        ),
        'data' => array()
    );
    if (empty($idEntrevista) || !is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar eventos de entrevista
        $object = new Evento();
        $object->setIdEntrevista($idEntrevista);

        $listado = $object->buscarEventosEntrevista($conn, $idioma);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            $payload['data'][] = array(
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
            );
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
})->add(new JwtMiddleware());
