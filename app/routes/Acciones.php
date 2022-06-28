<?php

//* Listado de acciones del sistema segun IDIOMA
$app->get('/acciones/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/acciones/idioma/" . $idioma
        ),
        'data' => array()
    );

    if ($conn !== null) {

        //Buscar acciones
        $object = new Acciones();
        $listado = $object->buscarTodosPorIdioma($conn, $idioma);

        //Preparar respuesta
        foreach ($listado as $value) {

            if ($idioma === "es") {
                $nombre = $value['nombre_es'];
            } else {
                $nombre = $value['nombre_en'];
            }

            $payload['data'][] = array(
                'type' => 'acciones',
                'id' => $value['id'],
                'attributes' => array('nombre' => $nombre)
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

//* Listado de acciones del sistema
$app->get('/acciones', function ($request, $response, $args) {

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/acciones"
        ),
        'data' => array()
    );

    if ($conn !== null) {

        //Buscar acciones
        $object = new Acciones();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'acciones',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre_es' => $value['nombre_es'],
                    'nombre_en' => $value['nombre_en']
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

//* Crear una accion
$app->post('/acciones', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/acciones'
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
    if (!array_key_exists('nombre_es', $data) || $data['nombre_es'] === "") {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_es is empty');
        $response = $response->withStatus(400);
    }
    if (!array_key_exists('nombre_en', $data) || $data['nombre_en'] === "") {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_en is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar accion

        $object = new Acciones();
        $object->setNombreEs(htmlspecialchars($data['nombre_es']));
        $object->setNombreEn(htmlspecialchars($data['nombre_en']));

        $lastId = $object->agregar($conn);

        //Insert error
        if (!$lastId) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {
            $object->setId($lastId);
            $accion = $object->buscarAccion($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $accion['id'],
                'attributes' => array(
                    'nombre_es' => $accion['nombre_es'],
                    'nombre_en' => $accion['nombre_en'],
                    'create_time' => $accion['create_time']
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

//* Editar o actualizar una accion
$app->put('/acciones/{id_accion}', function ($request, $response, $args) {

    $idAccion = $args['id_accion'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/acciones/" . $idAccion
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $putData = array();
    parse_str($data, $putData);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!array_key_exists('nombre_es', $putData) || $putData['nombre_es'] === "") {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_es is empty');
        $response = $response->withStatus(400);
    }
    if (!array_key_exists('nombre_en', $putData) || $putData['nombre_en'] === "") {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_en is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar accion
        $object = new Acciones();
        $object->setId(htmlspecialchars($idAccion));
        $object->setNombreES(htmlspecialchars($putData['nombre_es']));
        $object->setNombreEN(htmlspecialchars($putData['nombre_en']));

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
                    'nombre_es' => $acciones['nombre_es'],
                    'nombre_en' => $acciones['nombre_en'],
                    'update_time' => $acciones['update_time']
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

//* Eliminar una accion
$app->delete('/acciones/{id_accion}', function ($request, $response, $args) {

    $idAccion = $args['id_accion'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/acciones/" . $idAccion
        )
    );

    if (!is_numeric($idAccion)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        $object = new Acciones();
        $eliminar = $object->eliminar($conn, $idAccion);

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
