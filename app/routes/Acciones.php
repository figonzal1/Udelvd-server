<?php

/**
 * GET /acciones: Listado de acciones del sistema
 */
$app->get('/acciones[/]', function ($request, $response, $args) {

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/acciones"
        ),
        'data' => array()
    );

    if ($conn != null) {

        //Buscar acciones
        $object = new Acciones();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'acciones',
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

    return $response;
});


/**
 * GET /acciones/{id}: Obtener investigador segun id
 */
$app->get('/acciones/{id}', function ($request, $response, $args) {

    $id_accion = $args['id'];

    //conectar bd
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();


    $payload = array(
        'links' => array(
            'self' => "/acciones/" . $id_accion
        )
    );

    if (!is_numeric($id_accion)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar acciones
        $object = new Acciones();
        $object->setId($id_accion);

        $acciones = $object->buscarAccion($conn);

        //Si investigador no existe
        if (empty($acciones)) {
            $payload['data'] = array();
        }

        //Si el investigador existe
        else {
            $payload['data'] = array(
                'type' => 'acciones',
                'id' => $acciones['id'],
                'attributes' => array(
                    'nombre' => $acciones['nombre']
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
 * POST /acciones: Crear una accion
 */
$app->post('/acciones', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/investigadores'
        )
    );

    //Obtener parametros post
    $data = $request->getParsedBody();

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * VALIDACION PARAMETROS
     */
    if (!isset($data['nombre']) || empty($data['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar accion

        $object = new Acciones();
        $object->setNombre(htmlspecialchars($data['nombre']));

        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {
            $object->setId($lastid);
            $acciones = $object->buscarAccion($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $acciones['id'],
                'attributes' => array(
                    'nombre' => $acciones['nombre']
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
 * PUT /acciones/{id}: Editar una accion
 */

$app->put('/acciones/{id}', function ($request, $response, $args) {

    $id_accion = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/acciones/" . $id_accion
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $putdata = array();
    parse_str($data, $putdata);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!isset($putdata['nombre']) || empty($putdata['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar accion
        $object = new Acciones();
        $object->setId(htmlspecialchars($id_accion));
        $object->setNombre(htmlspecialchars($putdata['nombre']));

        //Actualizar investigador
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $acciones = $object->buscarAccion($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $acciones['id'],
                'attributes' => array(
                    'nombre' => $acciones['nombre']
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
 * DELETE /acciones/{id}: Eliminar una accion
 */

$app->delete('/acciones/{id}', function ($request, $response, $args) {

    $id_accion = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/acciones/" . $id_accion
        )
    );

    if (!is_numeric($id_accion)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        $object = new Acciones();
        $object->setId($id_accion);
        $eliminar = $object->eliminar($conn);

        if ($eliminar) {
            $response = $response->withStatus(200);
            $payload['data'] = array();
        }
        //Error de eliminacion
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
