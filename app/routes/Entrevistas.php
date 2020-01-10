<?php

/**
 * GET /entrevistados/{id_entrevistado}/entrevistas: Listado de entrevistas de una persona especifica
 */
$app->get('/entrevistados/{id_entrevistado}/entrevistas', function ($request, $response, $args) {

    $id_entrevistado = $args['id_entrevistado'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $id_entrevistado . "/entrevistas"
        ),
        'data' => array()
    );


    if (!isset($id_entrevistado) || empty($id_entrevistado) || !is_numeric($id_entrevistado)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar entrevistas de usuarios
        $object = new Entrevista();
        $object->setIdEntrevistado($id_entrevistado);
        $listado = $object->buscarEntrevistasPersonales($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'entrevistas',
                    'id' => $value['id'],
                    'attributes' => array(
                        'id_entrevistado' => $value['id_entrevistado'],
                        'id_tipo_entrevista' => $value['id_tipo_entrevista'],
                        'fecha_entrevista' =>  $value['fecha_entrevista']
                    ),
                    'relationships' => array(
                        'tipoEntrevista' => array(
                            'data' => array(
                                'id' => $value['id_tipo_entrevista'],
                                'nombre' => $value['nombre_tipo_entrevista']
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
});

/**
 * GET /entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}: Obtener una entrevista de una persona especifica
 */
$app->get('/entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}', function ($request, $response, $args) {

    $id_entrevistado = $args['id_entrevistado'];
    $id_entrevista = $args['id_entrevista'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $id_entrevistado . "/entrevistas/" . $id_entrevista
        ),
        'data' => array()
    );


    if (!isset($id_entrevistado) || empty($id_entrevistado) || !is_numeric($id_entrevistado)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevistado must be integer');
        $response = $response->withStatus(400);
    }
    if (!isset($id_entrevista) || empty($id_entrevista) || !is_numeric($id_entrevista)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar entrevistas de usuarios
        $object = new Entrevista();
        $object->setIdEntrevistado($id_entrevistado);
        $object->setId($id_entrevista);
        $entrevista = $object->buscarEntrevistaPersonal($conn);

        //Preparar respuesta
        $payload['data'] = array(
            'type' => 'entrevista',
            'id' => $entrevista['id'],
            'attributes' => array(
                'id_entrevistado' => $entrevista['id_entrevistado'],
                'id_tipo_entrevista' => $entrevista['id_tipo_entrevista'],
                'fecha_entrevista' =>  $entrevista['fecha_entrevista']
            )

        );
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server connection problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
});

/**
 * POST /entrevistados/{id}/entrevistas: Crear una entrevista
 */
$app->post('/entrevistados/{id_entrevistado}/entrevistas', function ($request, $response, $args) {

    $id_entrevista = $args['id_entrevistado'];

    $payload = array(
        'links' => array(
            'self' => '/entrevistados/' . $id_entrevista . '/entrevistas'
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
    if (!isset($id_entrevista) || empty($id_entrevista)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_tipo_entrevista']) || empty($data['id_tipo_entrevista'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_tipo_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['fecha_entrevista']) || empty($data['fecha_entrevista'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha entrevista is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar entrevista
        $object = new Entrevista();
        $object->setIdEntrevistado($id_entrevista);
        $object->setFechaEntrevista($data['fecha_entrevista']);
        $object->setIdTipoEntrevista($data['id_tipo_entrevista']);

        //Insertar entrevista
        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            //Buscar entrevista por id
            $object->setId($lastid);
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
});


/**
 * PUT /entrevistados/{id_entrevistado}/entrevistas/{id_entrevista}: Editar una entrevista
 */
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
    $putdata = array();
    parse_str($data, $putdata);

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
    } else if (!isset($putdata['fecha_entrevista']) || empty($putdata['fecha_entrevista'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'fecha_entrevista is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_tipo_entrevista']) || empty($putdata['id_tipo_entrevista'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_tipo_entrevista is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Actualzar entrevista
        $object = new Entrevista();
        $object->setIdEntrevistado($id_entrevistado);
        $object->setId($id_entrevista);
        $object->setFechaEntrevista($putdata['fecha_entrevista']);
        $object->setIdTipoEntrevista($putdata['id_tipo_entrevista']);

        //Actualizar enrevista
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
});

//TODO: PEndiente por corregir
/**
 * DELETE /usuarios/{id_usuario}/entrevistas/{id_entrevista}: Eliminar una entrevista
 */
$app->delete('/usuarios/{id_usuario}/entrevistas/{id_entrevista}', function ($request, $response, $args) {

    $id_usuario = $args['id_usuario'];
    $id_entrevista = $args['id_entrevista'];

    //Conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/usuarios/" . $id_usuario . "/entrevistas/" . $id_entrevista
        )
    );

    if (!isset($id_usuario) || empty($id_usuario) || !is_numeric($id_usuario)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_usuario must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($id_entrevista) || empty($id_entrevista) || !is_numeric($id_entrevista)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_entrevista must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Eliminar entrevistas
        $object  = new Entrevista();
        $object->setId($id_entrevista);
        $object->setIdEntrevistado($id_usuario);
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

    $payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;
});
