<?php
//TODO: FALTA AGREGAR ENFERMEDADES
/**
 * GET /usuarios: Listado de usuarios del sistema
 */
$app->get('/entrevistados[/]', function ($request, $response, $args) {

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados"
        ),
        'data' => array()
    );

    if ($conn != null) {
        //Buscar Entrevistados
        $object = new Entrevistado();
        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'entrevistados',
                    'id' => $value['id'],
                    'attributes' => array(
                        'nombre' => $value['nombre'],
                        'apellido' => $value['apellido'],
                        'sexo' => $value['sexo'],
                        'fecha_nacimiento' => $value['fecha_nacimiento'],
                        'ciudad' => $value['ciudad'],
                        'jubilado_legal' => $value['jubilado_legal'],
                        'caidas' => $value['caidas'],
                        'n_caidas' => $value['n_caidas'],
                        'n_convivientes_3_meses' => $value['n_convivientes_3_meses'],
                        'id_investigador' => $value['id_investigador'],
                        'id_nivel_educacional' => $value['id_nivel_educacional'],
                        'id_estado_civil' => $value['id_estado_civil'],
                        'id_conviviente' => $value['id_conviviente'],
                        'id_profesion' => $value['id_profesion']
                    )
                )
            );
        }

        $response = $response->withStatus(200);
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
}); //->add(new JwtMiddleware());

/**
 * GET /entrevistados/{id}: Obtener usuario segun id
 */
$app->get('/entrevistados/{id}', function ($request, $response, $args) {

    $id_entrevistado = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $id_entrevistado
        )
    );

    if (!isset($id_entrevistado) || !is_numeric($id_entrevistado) ||  empty($id_entrevistado)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Buscar usuario
        $object = new Entrevistado();
        $object->setId($id_entrevistado);
        $entrevistado = $object->buscarEntrevistado($conn);

        //Si usuario no existe
        if (empty($entrevistado)) {
            $payload['data'] = array();
        }

        //Si el usuario existe
        else {
            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'entrevistados',
                'id' => $entrevistado['id'],
                'attributes' => array(
                    'nombre' => $entrevistado['nombre'],
                    'apellido' => $entrevistado['apellido'],
                    'sexo' => $entrevistado['sexo'],
                    'fecha_nacimiento' => $entrevistado['fecha_nacimiento'],
                    'ciudad' => $entrevistado['ciudad'],
                    'jubilado_legal' => $entrevistado['jubilado_legal'],
                    'caidas' => $entrevistado['caidas'],
                    'n_caidas' => $entrevistado['n_caidas'],
                    'n_convivientes_3_meses' => $entrevistado['n_convivientes_3_meses'],
                    'id_investigador' => $entrevistado['id_investigador'],
                    'id_nivel_educacional' => $entrevistado['id_nivel_educacional'],
                    'id_estado_civil' => $entrevistado['id_estado_civil'],
                    'id_conviviente' => $entrevistado['id_conviviente'],
                    'id_profesion' => $entrevistado['id_profesion']
                )
            );

            $response = $response->withStatus(200);
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
 * POST /entrevistados: Crear un entrevistos
 */
$app->post('/entrevistados', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/entrevistados'
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

    //CAMPOS OPCIONALES
    if (!isset($data['n_caidas']) || empty($data['n_caidas'])) {
        $data['n_caidas'] = NULL;
    }
    if (!isset($data['id_nivel_educacional']) || empty($data['id_nivel_educacional'])) {
        $data['id_nivel_educacional'] = NULL;
    }
    if (!isset($data['id_conviviente']) || empty($data['id_conviviente'])) {
        $data['id_conviviente'] = NULL;
    }

    //CAMPOS OBLIGATORIOS
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
    } else if (!isset($data['fecha_nacimiento']) || empty($data['fecha_nacimiento'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha_nac is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['ciudad']) || empty($data['ciudad'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Ciudad is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['jubilado_legal'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado legal is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['jubilado_legal'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado_legal must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($data['nombre_profesion'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_profesion is empty');
        $response = $response->withStatus(400);
    } else if (!isset($data['caidas'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['caidas'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_estado_civil']) || empty($data['id_estado_civil'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['id_estado_civil'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($data['id_investigador']) || empty($data['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Agregar Entrevistado
        $object = new Entrevistado();
        $object->setNombre(htmlspecialchars($data['nombre']));
        $object->setApellido(htmlspecialchars($data['apellido']));
        $object->setSexo(htmlspecialchars($data['sexo']));
        $object->setFechaNac($data['fecha_nacimiento']);
        $object->setCiudad(htmlspecialchars($data['ciudad']));
        $object->setJubiladoLegal(htmlspecialchars($data['jubilado_legal']));
        $object->setCaidas(htmlspecialchars($data['caidas']));
        $object->setNConvivientes(htmlspecialchars($data['n_convivientes_3_meses']));
        $object->setIdInvestigador(htmlspecialchars($data['id_investigador']));
        $object->setIdEstadoCivil(htmlspecialchars($data['id_estado_civil']));

        //Opcionales
        $object->setNCaidas($data['n_caidas']);
        $object->setIdNivelEducacional($data['id_nivel_educacional']);
        $object->setidConviviente($data['id_conviviente']);
        //$object->setIdProfesion($data['id_profesion']);
        $object->setNombreProfesion(htmlspecialchars(ucfirst($data['nombre_profesion'])));

        //insertar usuario
        $lastid = $object->agregar($conn);

        //Insert error
        if (!$lastid) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastid);
            $usuario = $object->buscarEntrevistado($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'usuarios',
                'id' => $usuario['id'],
                'attributes' => array(
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'sexo' => $usuario['sexo'],
                    'fecha_nacimiento' => $usuario['fecha_nacimiento'],
                    'ciudad' => $usuario['ciudad'],
                    'jubilado_legal' => $usuario['jubilado_legal'],
                    'caidas' => $usuario['caidas'],
                    'n_caidas' => $usuario['n_caidas'],
                    'n_convivientes_3_meses' => $usuario['n_convivientes_3_meses'],

                    'id_investigador' => $usuario['id_investigador'],
                    'id_nivel_educacional' => $usuario['id_nivel_educacional'],
                    'id_estado_civil' => $usuario['id_estado_civil'],
                    'id_conviviente' => $usuario['id_conviviente'],
                    'id_profesion' => $usuario['id_profesion']
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

/**
 * PUT /entrevistados/{id}: Editar un entrevistados
 */
$app->put('/entrevistados/{id}', function ($request, $response, $args) {

    $id_entrevistado = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $id_entrevistado
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
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    }

    //CAMPOS OPCIONALES
    if (!isset($putdata['n_caidas']) || empty($putdata['n_caidas'])) {
        $putdata['n_caidas'] = NULL;
    }
    if (!isset($putdata['id_nivel_educacional']) || empty($putdata['id_nivel_educacional'])) {
        $putdata['id_nivel_educacional'] = NULL;
    }
    if (!isset($putdata['id_conviviente']) || empty($putdata['id_conviviente'])) {
        $putdata['id_conviviente'] = NULL;
    }
    if (!isset($putdata['id_profesion']) || empty($putdata['id_profesion'])) {
        $putdata['id_profesion'] = NULL;
    }

    //Campos obligatorios
    if (!isset($putdata['nombre']) || empty($putdata['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['apellido']) || empty($putdata['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['sexo']) || empty($putdata['sexo'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Sexo is empty');
        $response = $response->withStatus(400);
    } else if (strcmp($putdata['sexo'], "m") != 0 && strcmp($putdata['sexo'], "f") != 0) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', "Sexo must be 'm' or 'f'");
        $response = $response->withStatus(400);
    } else if (!isset($putdata['fecha_nacimiento']) || empty($putdata['fecha_nacimiento'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha_nac is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['ciudad']) || empty($putdata['ciudad'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Ciudad is empty');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['jubilado_legal'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado legal is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putdata['jubilado_legal'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado_legal must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['caidas'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putdata['caidas'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_estado_civil']) || empty($putdata['id_estado_civil'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putdata['id_estado_civil'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil must be integer');
        $response = $response->withStatus(400);
    } else if (!isset($putdata['id_investigador']) || empty($putdata['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putdata['id_investigador'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        //Actualizar Entrevistado
        $object = new Entrevistado();
        $object->setId(htmlspecialchars($id_entrevistado));
        $object->setNombre(htmlspecialchars($putdata['nombre']));
        $object->setApellido(htmlspecialchars($putdata['apellido']));
        $object->setSexo(htmlspecialchars($putdata['sexo']));
        $object->setFechaNac($putdata['fecha_nacimiento']);
        $object->setCiudad(htmlspecialchars($putdata['ciudad']));
        $object->setJubiladoLegal(htmlspecialchars($putdata['jubilado_legal']));
        $object->setCaidas(htmlspecialchars($putdata['caidas']));
        $object->setNConvivientes(htmlspecialchars($putdata['n_convivientes_3_meses']));
        $object->setIdInvestigador(htmlspecialchars($putdata['id_investigador']));
        $object->setIdEstadoCivil(htmlspecialchars($putdata['id_estado_civil']));


        //Opcionales
        $object->setNCaidas($putdata['n_caidas']);
        $object->setIdNivelEducacional($putdata['id_nivel_educacional']);
        $object->setIdConviviente($putdata['id_conviviente']);
        $object->setIdProfesion($putdata['id_profesion']);

        //actualizar entrevistado
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $entrevistado = $object->buscarEntrevistado($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'usuarios',
                'id' => $entrevistado['id'],
                'attributes' => array(
                    'nombre' => $entrevistado['nombre'],
                    'apellido' => $entrevistado['apellido'],
                    'sexo' => $entrevistado['sexo'],
                    'fecha_nacimiento' => $entrevistado['fecha_nacimiento'],
                    'ciudad' => $entrevistado['ciudad'],
                    'jubilado_legal' => $entrevistado['jubilado_legal'],
                    'caidas' => $entrevistado['caidas'],
                    'n_caidas' => $entrevistado['n_caidas'],
                    'n_convivientes_3_meses' => $entrevistado['n_convivientes_3_meses'],

                    'id_investigador' => $entrevistado['id_investigador'],
                    'id_nivel_educacional' => $entrevistado['id_nivel_educacional'],
                    'id_estado_civil' => $entrevistado['id_estado_civil'],
                    'id_conviviente' => $entrevistado['id_conviviente'],
                    'id_profesion' => $entrevistado['id_profesion']
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

/**
 * DELETE /entrevistados: Eliminar un usuario
 */
$app->delete('/entrevistados/{id}', function ($request, $response, $args) {

    $id_entrevistado = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistado/" . $id_entrevistado
        )
    );

    if (!isset($id_entrevistado) || !is_numeric($id_entrevistado) || empty($id_entrevistado)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn != null) {

        $object = new Entrevistado();
        $object->setId($id_entrevistado);
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
})->add(new JwtMiddleware());
