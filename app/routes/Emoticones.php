<?php

//* Listado de emoticones del sistema
$app->get('/emoticones/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/emoticones/" . $idioma
        ),
        'data' => array()
    );

    if ($conn != null) {
        //Buscar emoticones
        $object = new Emoticones();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            if ($idioma == 'es') {
                $descripcion = $value['descripcion_es'];
            } else if ($idioma == 'en') {
                $descripcion = $value['descripcion_en'];
            }

            array_push(
                $payload['data'],
                array(
                    'type' => 'emoticones',
                    'id' => $value['id'],
                    'attributes' => array(
                        'url' => $value['url'],
                        'descripcion' => $descripcion
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

//TODO: REVISAR e implementar en android
//* Obtener emoticones segun id
/*$app->get('/emoticones/{id}/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];

    $id_emoticon = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/emoticones/" . $id_emoticon . "/" . $idioma
        )
    );

    if (!isset($id_emoticon) || empty($id_emoticon) || !is_numeric($id_emoticon)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar emoticones
        $object = new Emoticones();
        $object->setId($id_emoticon);
        $emoticon = $object->buscaremoticon($conn);

        //Si emoticon no existe
        if (empty($emoticon)) {
            $payload['data'] = array();
        }

        //Si el emoticon existe
        else {

            if ($idioma == 'es') {
                $descripcion = $emoticon['descripcion_es'];
            } else if ($idioma == 'en') {
                $descripcion = $emoticon['descripcion_en'];
            }

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'emoticones',
                'id' => $emoticon['id'],
                'attributes' => array(
                    'url' => $emoticon['url'],
                    'descripcion' => $descripcion
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
});*/

//TODO: REVISAR e implementar en android , agregar soporte idioma
//* Crear emoticones
/*$app->post('/emoticones', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/emoticones'
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
    /*if (!isset($data['url']) || empty($data['url'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Url is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['descripcion']) || empty($data['descripcion'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Descripcion is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar emoticon
        $object = new Emoticones();
        $object->setUrl(htmlspecialchars($data['url']));
        $object->setDescripcion(htmlentities($data['descripcion']));

        //insertar emoticon
        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastid);
            $emoticon = $object->buscarEmoticon($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'emoticones',
                'id' => $emoticon['id'],
                'attributes' => array(
                    'url' => $emoticon['url'],
                    'descripcion' => $emoticon['descripcion']
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
});*/

//TODO: Revisar e implentar en android, agregar soporte idioma
//* Editar un emoticon
/*$app->put('/emoticones/{id}', function ($request, $response, $args) {

    $id_emoticon = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/emoticon/" . $id_emoticon
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
    /*if (!isset($id_emoticon) || empty($id_emoticon) || !is_numeric($id_emoticon)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['url']) || empty($putdata['url'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Url is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['descripcion']) || empty($putdata['descripcion'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Descripcion is empty');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar emoticon
        $object = new Emoticones();
        $object->setId(htmlentities($id_emoticon));
        $object->setUrl(htmlentities($putdata['url']));
        $object->setDescripcion(htmlentities($putdata['descripcion']));

        //Actualizar emoticon
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $emoticon = $object->buscarEmoticon($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'emoticones',
                'id' => $emoticon['id'],
                'attributes' => array(
                    'url' => $emoticon['url'],
                    'descripcion' => $emoticon['descripcion']
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
});*/

//TODO: Revisar e implementar en android, agregar soporte de idioma
//* Eliminar un emoticon
/*$app->delete('/emoticones/{id}', function ($request, $response, $args) {

    $id_emoticon = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/emoticones/" . $id_emoticon
        )
    );

    if (!isset($id_emoticon) || empty($id_emoticon) || !is_numeric($id_emoticon)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        $object = new Emoticones();
        $object->setId($id_emoticon);
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
});*/
