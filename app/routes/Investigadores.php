<?php

require_once("app/config/MysqlAdapter.php");
require_once("app/class/Investigador.php");
require_once("app/utils/ErrorJsonHandler.php");
/**
 * GET /investigadores: Listado de investigadores del sistema
 */
$app->get('/investigadores[/]', function ($request, $response, $args) {

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores"
        )
    );

    if ($conn != null) {
        //Buscar investigadores
        $object = new Investigador();
        $listado = $object->buscarTodos($conn);

        //Si investigador no existe
        if (empty($listado)) {
            $payload['data'] = array();
        }

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'investigadores',
                    'id' => $value['id'],
                    'attributes' => array(
                        'nombre' => $value['nombre'],
                        'apellido' => $value['apellido'],
                        'email' => $value['email'],
                        'id_rol' => $value['id_rol'],
                        'activado' => $value['activado']
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

/**
 * GET /investigadores/{id}: Obtener investigador segun id
 */
$app->get('/investigadores/{id}', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador
        )
    );

    if (!filter_var($id_investigador, FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar investigadores
        $object = new Investigador();
        $object->setId($id_investigador);
        $investigador = $object->buscarInvestigador($conn);

        //Si investigador no existe
        if (empty($investigador)) {
            $payload['data'] = array();
        }

        //Si el investigador existe
        else {
            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'id_rol' => $investigador['id_rol'],
                    'activado' => $investigador['activado']
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
 * POST /investigadores: Crear un investigador
 */
$app->post('/investigadores', function ($request, $response, $args) {


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
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is malformed');
        $response = $response->withStatus(400);
    } else if (empty($data['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['id_rol'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if (!filter_var($data['id_rol'], FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar investigador
        $object = new Investigador();
        $object->setNombre(htmlentities($data['nombre']));
        $object->setApellido(htmlentities($data['apellido']));
        $object->setEmail(htmlentities($data['email']));
        $object->setIdRol(htmlentities($data['id_rol']));
        $object->setPassword(htmlentities($data['password']));
        $object->setActivado(0);

        //insertar investigador
        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastid);
            $investigador = $object->buscarInvestigador($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'id_rol' => $investigador['id_rol'],
                    'activado' => $investigador['activado']
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
 * PUT /investigadores/{id}: Editar un investigador
 */
$app->put('/investigadores/{id}', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador
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
    if (!filter_var($putdata['email'], FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is malformed');
        $response = $response->withStatus(400);
    } else if (!filter_var($id_investigador, FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if (empty($putdata['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (empty($putdata['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (empty($putdata['id_rol'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol is empty');
        $response = $response->withStatus(400);
    } else if (empty($putdata['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if (!filter_var($putdata['id_rol'], FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar investigador
        $object = new Investigador();
        $object->setId(htmlentities($id_investigador));
        $object->setNombre(htmlentities($putdata['nombre']));
        $object->setApellido(htmlentities($putdata['apellido']));
        $object->setEmail(htmlentities($putdata['email']));
        $object->setIdRol(htmlentities($putdata['id_rol']));
        $object->setPassword(htmlentities($putdata['password']));

        //Actualizar investigador
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $investigador = $object->buscarInvestigador($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'id_rol' => $investigador['id_rol'],
                    'activado' => $investigador['activado']
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
 * DELETE /investigadores: Eliminar un investigador
 */
$app->delete('/investigadores/{id}', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador
        )
    );

    if (!filter_var($id_investigador, FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        $object = new Investigador();
        $object->setId($id_investigador);
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


/**
 * PATCH /investigador/{id}/activar: Activar registro de inevstigador
 */
$app->patch('/investigadores/{id}/activar', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador . "/activar"
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $patchdata = array();
    parse_str($data, $patchdata);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!isset($patchdata['activado']) || !is_numeric($patchdata['activado']) || empty($patchdata['activado'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Activado must be integer');
        $response = $response->withStatus(400);
    } else if ($patchdata['activado'] != 0 && $patchdata['activado'] != 1) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Activado must be 1 or 0');
        $response = $response->withStatus(400);
    } else if (!filter_var($id_investigador, FILTER_VALIDATE_INT)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar investigador
        $object = new Investigador();
        $object->setId($id_investigador);
        $object->setActivado(htmlentities($patchdata['activado']));

        //insertar investigador
        $activado = $object->activar($conn);

        //Insert error
        if (!$activado) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Activate problem', 'Activate a investigator has fail');
            $response = $response->withStatus(500);
        } else {

            $investigador = $object->buscarInvestigador($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'id_rol' => $investigador['id_rol'],
                    'activado' => $investigador['activado']
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
