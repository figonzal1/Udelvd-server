<?php

require_once("app/config/MysqlAdapter.php");
require_once("app/class/Usuario.php");
require_once("app/utils/ErrorJsonHandler.php");

/**
 * GET /usuarios: Listado de usuarios del sistema
 */
$app->get('/usuarios[/]', function ($request, $response, $args) {

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/usuarios"
        ),
        'data' => array()
    );

    if ($conn != null) {
        //Buscar Usuarios
        $object = new Usuario();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'usuarios',
                    'id' => $value['id'],
                    'attributes' => array(
                        'nombre' => $value['nombre'],
                        'apellido' => $value['apellido'],
                        'sexo' => $value['sexo'],
                        'edad' => $value['edad'],
                        'ciudad' => $value['ciudad'],
                        'id_investigador' => $value['id_investigador']
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
 * GET /usuarios/{id}: Obtener usuario segun id
 */
$app->get('/usuarios/{id}', function ($request, $response, $args) {

    $id_usuario = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/usuarios/" . $id_usuario
        )
    );

    if (!isset($id_usuario) || !is_numeric($id_usuario) ||  empty($id_usuario)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar usuario
        $object = new Usuario();
        $object->setId($id_usuario);
        $usuario = $object->buscarUsuario($conn);

        //Si usuario no existe
        if (empty($usuario)) {
            $payload['data'] = array();
        }

        //Si el usuario existe
        else {
            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'usuarios',
                'id' => $usuario['id'],
                'attributes' => array(
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'sexo' => $usuario['sexo'],
                    'edad' => $usuario['edad'],
                    'ciudad' => $usuario['ciudad'],
                    'id_investigador' => $usuario['id_investigador']
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
 * POST /usuarios: Crear un usuario
 */
$app->post('/usuarios', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/usuarios'
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
    } else if (!isset($data['apellido']) || empty($data['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['sexo']) || empty($data['sexo'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Sexo is empty');
        $response = $response->withStatus(400);
    } else if (strcmp($data['sexo'], "m") != 0 && strcmp($data['sexo'], "f") != 0) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', "Sexo must be 'm' or 'f'");
        $response = $response->withStatus(400);
    } else if (!isset($data['edad']) || empty($data['edad'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Edad is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['ciudad']) || empty($data['ciudad'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Ciudad is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_investigador']) || empty($data['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar Usuario
        $object = new Usuario();
        $object->setNombre(htmlspecialchars($data['nombre']));
        $object->setApellido(htmlspecialchars($data['apellido']));
        $object->setSexo(htmlspecialchars($data['sexo']));
        $object->setEdad(htmlspecialchars($data['edad']));
        $object->setCiudad(htmlspecialchars($data['ciudad']));
        $object->setIdInvestigador(htmlspecialchars($data['id_investigador']));

        //insertar usuario
        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastid);
            $usuario = $object->buscarUsuario($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'usuarios',
                'id' => $usuario['id'],
                'attributes' => array(
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'sexo' => $usuario['sexo'],
                    'edad' => $usuario['edad'],
                    'ciudad' => $usuario['ciudad'],
                    'id_investigador' => $usuario['id_investigador']
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
 * PUT /usuarios/{id}: Editar un usuario
 */
$app->put('/usuarios/{id}', function ($request, $response, $args) {

    $id_usuario = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/usuarios/" . $id_usuario
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
    if (!is_numeric($id_usuario)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['nombre']) || empty($putdata['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['apellido']) || empty($putdata['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['sexo']) || empty($putdata['sexo'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Sexo is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['ciudad']) || empty($putdata['ciudad'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Ciudad is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_investigador']) || empty($putdata['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putdata['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar Usuario
        $object = new Usuario();
        $object->setNombre(htmlspecialchars($data['nombre']));
        $object->setApellido(htmlspecialchars($data['apellido']));
        $object->setSexo(htmlspecialchars($data['sexo']));
        $object->setEdad(htmlspecialchars($data['edad']));
        $object->setCiudad(htmlspecialchars($data['ciudad']));
        $object->setIdInvestigador(htmlspecialchars($data['id_investigador']));

        //insertar usuario
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $usuario = $object->buscarUsuario($conn);

            $payload['data'] = array(
                'type' => 'usuarios',
                'id' => $usuario['id'],
                'attributes' => array(
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'sexo' => $usuario['sexo'],
                    'edad' => $usuario['edad'],
                    'ciudad' => $usuario['ciudad'],
                    'id_investigador' => $usuario['id_investigador']
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
 * DELETE /usuarios: Eliminar un usuario
 */
$app->delete('/usuarios/{id}', function ($request, $response, $args) {

    $id_usuario = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/usuarios/" . $id_usuario
        )
    );

    if (!isset($id_usuario) || !is_numeric($id_usuario) || empty($id_usuario)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        $object = new Usuario();
        $object->setId($id_usuario);
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
