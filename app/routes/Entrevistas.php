<?php

//* Listado de entrevistas de una persona especifica
$app->get('/entrevistados/{id_entrevistado}/entrevistas/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    $idEntrevistado = $args['id_entrevistado'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $idEntrevistado . "/entrevistas/idioma/" . $idioma
        ),
        'data' => array()
    );


    if (empty($idEntrevistado) || !is_numeric($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar entrevistas de usuarios
        $object = new Entrevista();
        $object->setIdEntrevistado($idEntrevistado);

        $listado = $object->buscarEntrevistasPersonales($conn, $idioma);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'entrevistas',
                'id' => $value['id'],
                'attributes' => array(
                    'id_entrevistado' => $value['id_entrevistado'],
                    'id_tipo_entrevista' => $value['id_tipo_entrevista'],
                    'fecha_entrevista' => $value['fecha_entrevista']
                ),
                'relationships' => array(
                    'tipoEntrevista' => array(
                        'data' => array(
                            'id' => $value['id_tipo_entrevista'],
                            'nombre' => $value['nombre_tipo_entrevista']
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

//* Obtener una entrevista de una persona especifica
$app->get('/entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}', function ($request, $response, $args) {

    $idEntrevistado = $args['id_entrevistado'];
    $idEntrevista = $args['id_entrevista'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $idEntrevistado . "/entrevistas/" . $idEntrevista
        ),
        'data' => array()
    );


    if (empty($idEntrevistado) || !is_numeric($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    }
    if (empty($idEntrevista) || !is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar entrevistas de usuarios
        $object = new Entrevista();
        $object->setIdEntrevistado($idEntrevistado);
        $object->setId($idEntrevista);

        $entrevista = $object->buscarEntrevistaPersonal($conn);

        //Si entrevista no existe
        if (empty($entrevista)) {
            $payload['data'] = array();
        } else {
            //Preparar respuesta
            $payload['data'] = array(
                'type' => 'entrevista',
                'id' => $entrevista['id'],
                'attributes' => array(
                    'id_entrevistado' => $entrevista['id_entrevistado'],
                    'id_tipo_entrevista' => $entrevista['id_tipo_entrevista'],
                    'fecha_entrevista' => $entrevista['fecha_entrevista']
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

//* Crear una entrevista
$app->post('/entrevistados/{id_entrevistado}/entrevistas', function ($request, $response, $args) {

    $idEntrevistado = $args['id_entrevistado'];

    $payload = array(
        'links' => array(
            'self' => '/entrevistados/' . $idEntrevistado . '/entrevistas'
        )
    );

    //Obtener parametros post
    $data = $request->getParsedBody();

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * Validacion de parametros
     */
    if (empty($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['id_tipo_entrevista'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_tipo_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['fecha_entrevista'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha entrevista is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar entrevista
        $object = new Entrevista();
        $object->setIdEntrevistado($idEntrevistado);
        $object->setFechaEntrevista($data['fecha_entrevista']);
        $object->setIdTipoEntrevista($data['id_tipo_entrevista']);

        //Insertar entrevista
        $lastId = $object->agregar($conn);

        //Insert error
        if (!$lastId) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            //Buscar entrevista por id
            $object->setId($lastId);
            $entrevista = $object->buscarEntrevista($conn);

            //Formatear respuesta
            $payload['data'] = array(

                'type' => 'entrevistas',
                'id' => $entrevista['id'],
                'attributes' => array(
                    'id_entrevistado' => $entrevista['id_entrevistado'],
                    'id_tipo_entrevista' => $entrevista['id_tipo_entrevista'],
                    'fecha_entrevista' => $entrevista['fecha_entrevista'],
                    'create_time' => $entrevista['create_time']
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

//* Editar una entrevista
$app->put('/entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}', function ($request, $response, $args) {

    $id_entrevistado = $args['id_entrevistado'];
    $id_entrevista = $args['id_entrevista'];

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $id_entrevistado . "/entrevistas/" . $id_entrevista
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
    if (!is_numeric($id_entrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    } else if (!is_numeric($id_entrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if (empty($putData['fecha_entrevista'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'fecha_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['id_tipo_entrevista'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_tipo_entrevista is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Actualzar entrevista
        $object = new Entrevista();
        $object->setIdEntrevistado($id_entrevistado);
        $object->setId($id_entrevista);
        $object->setFechaEntrevista($putData['fecha_entrevista']);
        $object->setIdTipoEntrevista($putData['id_tipo_entrevista']);

        //Actualizar entrevista
        $actualizar = $object->actualizar($conn);

        if (!$actualizar) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $entrevista = $object->buscarEntrevista($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'entrevistas',
                'id' => $entrevista['id'],
                'attributes' => array(
                    'id_entrevistado' => $entrevista['id_entrevistado'],
                    'id_tipo_entrevista' => $entrevista['id_tipo_entrevista'],
                    'fecha_entrevista' => $entrevista['fecha_entrevista'],
                    'update_time' => $entrevista['update_time']
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

//* Eliminar una entrevista
$app->delete('/entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}', function ($request, $response, $args) {

    $idEntrevistado = $args['id_entrevistado'];
    $idEntrevista = $args['id_entrevista'];

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $idEntrevistado . "/entrevistas/" . $idEntrevista
        )
    );

    if (empty($idEntrevistado) || !is_numeric($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    } else if (empty($idEntrevista) || !is_numeric($idEntrevista)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Eliminar entrevistas
        $object = new Entrevista();
        $object->setId($idEntrevista);
        $object->setIdEntrevistado($idEntrevistado);

        $eliminar = $object->eliminar($conn);

        if ($eliminar) {

            $response = $response->withStatus(200);
            $payload['data'] = array();
        } //Error de eliminacion
        else {
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
