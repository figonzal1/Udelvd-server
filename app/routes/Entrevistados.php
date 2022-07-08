<?php /** @noinspection PhpConditionCheckedByNextConditionInspection */

//Crear un entrevistado
$app->post('/entrevistados', function ($request, $response, $args) {

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
    $nivelEducacional = $data['id_nivel_educacional'] ?? null;
    $tipoConvivencia = $data['id_tipo_convivencia'] ?? null;
    $nCaidas = $data['n_caidas'] ?? null;

    if (array_key_exists('nombre_profesion', $data)) {
        $nombreProfesion = htmlspecialchars(ucfirst($data['nombre_profesion']));
    } else {
        $nombreProfesion = null;
    }

    if (!array_key_exists('nombre', $data) || $data['nombre'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('apellido', $data) || $data['apellido'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('sexo', $data) || $data['sexo'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Sexo is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('fecha_nacimiento', $data) || $data['fecha_nacimiento'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha_nac is empty');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('nombre_ciudad', $data) || $data['nombre_ciudad'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_ciudad is empty');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('jubilado_legal', $data) || $data['jubilado_legal'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado legal is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($data['jubilado_legal'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado_legal must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('caidas', $data) || $data['caidas'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($data['caidas'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('id_estado_civil', $data) || $data['id_estado_civil'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($data['id_estado_civil'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('id_investigador', $data) || $data['id_investigador'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($data['id_investigador'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar Entrevistado
        $object = new Entrevistado();
        $object->setNombre(htmlspecialchars($data['nombre']));
        $object->setApellido(htmlspecialchars($data['apellido']));
        $object->setSexo(htmlspecialchars($data['sexo']));
        $object->setFechaNac($data['fecha_nacimiento']);
        $object->setNombreCiudad(htmlspecialchars(ucwords($data['nombre_ciudad'])));
        $object->setJubiladoLegal(htmlspecialchars($data['jubilado_legal']));
        $object->setCaidas(htmlspecialchars($data['caidas']));
        $object->setNConvivientes(htmlspecialchars($data['n_convivientes_3_meses']));
        $object->setIdInvestigador(htmlspecialchars($data['id_investigador']));
        $object->setIdEstadoCivil(htmlspecialchars($data['id_estado_civil']));

        //Opcionales
        $object->setNCaidas($nCaidas);
        $object->setIdNivelEducacional($nivelEducacional);
        $object->setIdTipoConvivencia($tipoConvivencia);
        $object->setNombreProfesion($nombreProfesion);

        //insertar usuario
        $lastId = $object->agregar($conn);

        //Insert error
        if (!$lastId) {

            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {

            $object->setId($lastId);
            $usuario = $object->buscarEntrevistado($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'entrevistados',
                'id' => $usuario['id'],
                'attributes' => array(
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'sexo' => $usuario['sexo'],
                    'fecha_nacimiento' => $usuario['fecha_nacimiento'],
                    'jubilado_legal' => $usuario['jubilado_legal'],
                    'caidas' => $usuario['caidas'],
                    'n_caidas' => $usuario['n_caidas'],
                    'n_convivientes_3_meses' => $usuario['n_convivientes_3_meses'],
                    'id_investigador' => $usuario['id_investigador'],
                    'id_ciudad' => $usuario['id_ciudad'],
                    'id_nivel_educacional' => $usuario['id_nivel_educacional'],
                    'id_estado_civil' => $usuario['id_estado_civil'],
                    'id_tipo_convivencia' => $usuario['id_tipo_convivencia'],
                    'id_profesion' => $usuario['id_profesion'],
                    'create_time' => $usuario['create_time']
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

//Listado de entrevistados totales del sistema
$app->get('/entrevistados/pagina/{n_pag}', function ($request, $response, $args) {

    $n_pag = $args['n_pag'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/pagina/" . $n_pag
        ),
        'data' => array()
    );

    if ($conn !== null) {

        //Buscar entrevistados
        $object = new Entrevistado();

        $listado = $object->buscarTodosConPagina($conn, $n_pag);

        $conteo = $object->contarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'entrevistados',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $value['nombre'],
                    'apellido' => $value['apellido'],
                    'sexo' => $value['sexo'],
                    'fecha_nacimiento' => $value['fecha_nacimiento'],
                    'jubilado_legal' => $value['jubilado_legal'],
                    'caidas' => $value['caidas'],
                    'n_caidas' => $value['n_caidas'],
                    'n_convivientes_3_meses' => $value['n_convivientes_3_meses'],
                    'id_investigador' => $value['id_investigador'],
                    'id_ciudad' => $value['id_ciudad'],
                    'id_nivel_educacional' => $value['id_nivel_educacional'],
                    'id_estado_civil' => $value['id_estado_civil'],
                    'id_tipo_convivencia' => $value['id_tipo_convivencia'],
                    'id_profesion' => $value['id_profesion']
                ),
                'relationships' => array(
                    'entrevistas' => array(
                        'data' => array(
                            'n_entrevistas' => $value['n_entrevistas']
                        )
                    ),
                    'investigadores' => array(
                        'data' => array(
                            'nombre' => $value['nombre_investigador'],
                            'apellido' => $value['apellido_investigador']
                        )
                    )

                )
            );
        }

        $payload['entrevistados'] = array(
            'data' => array(
                'n_entrevistados' => $conteo
            )
        );

        $response = $response->withStatus(200);
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

//Listado de entrevistados del sistema por paginacion e Investigador logeado
$app->get('/entrevistados/pagina/{n_pag}/investigador/{id_investigador}', function ($request, $response, $args) {

    $id_investigador = $args['id_investigador'];
    $n_pag = $args['n_pag'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/pagina/" . $n_pag . "/investigador/" . $id_investigador
        ),
        'data' => array()
    );

    if (!is_numeric($id_investigador) || empty($id_investigador)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar entrevistados
        $object = new Entrevistado();
        $object->setIdInvestigador($id_investigador);

        $listado = $object->buscarEntrevistadosInvestigadorPorPagina($conn, $n_pag);

        $conteo = $object->contarEntrevistadosDeInvestigador($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'entrevistados',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $value['nombre'],
                    'apellido' => $value['apellido'],
                    'sexo' => $value['sexo'],
                    'fecha_nacimiento' => $value['fecha_nacimiento'],
                    'jubilado_legal' => $value['jubilado_legal'],
                    'caidas' => $value['caidas'],
                    'n_caidas' => $value['n_caidas'],
                    'n_convivientes_3_meses' => $value['n_convivientes_3_meses'],
                    'id_investigador' => $value['id_investigador'],
                    'id_ciudad' => $value['id_ciudad'],
                    'id_nivel_educacional' => $value['id_nivel_educacional'],
                    'id_estado_civil' => $value['id_estado_civil'],
                    'id_tipo_convivencia' => $value['id_tipo_convivencia'],
                    'id_profesion' => $value['id_profesion']
                ),
                'relationships' => array(
                    'entrevistas' => array(
                        'data' => array(
                            'n_entrevistas' => $value['n_entrevistas']
                        )
                    )
                )
            );
        }

        $payload['entrevistados'] = array(
            'data' => array(
                'n_entrevistados' => $conteo
            )
        );
        $response = $response->withStatus(200);
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


//Obtener entrevistados con eventos registrados
$app->get('/entrevistados/eventos[/proyecto/{ids}]', function ($request, $response, $args) {

    $payload = array(
        'links' => array(
            'self' => '/entrevistados/eventos'
        )
    );

    $projectIds = $args['ids'] ?? null;

    if ($projectIds !== null) {
        $payload['links']['self'] .= "/proyecto/" . $projectIds;
        $projectIds = explode(';', $projectIds);
    }

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if ($conn !== null) {
        //Buscar entrevistados
        $object = new Entrevistado();

        $listado = $object->entrevistadosConEventos($conn, $projectIds);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'entrevistados',
                'id' => $value['id'],
                'attributes' => array(
                    'id' => $value['id'],
                    'nombre' => $value['nombre'],
                    'apellido' => $value['apellido'],
                    'n_eventos' => $value['n_eventos'],
                )
            );
        }
        $response = $response->withStatus(200);
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

//Obtener entrevistado segun id
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

    if (!isset($id_entrevistado) || !is_numeric($id_entrevistado) || empty($id_entrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar usuario
        $object = new Entrevistado();
        $object->setId($id_entrevistado);
        $entrevistado = $object->buscarEntrevistado($conn);

        //Si usuario no existe
        if (empty($entrevistado)) {
            $payload['data'] = array();
        } //Si el usuario existe
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
                    'jubilado_legal' => $entrevistado['jubilado_legal'],
                    'caidas' => $entrevistado['caidas'],
                    'n_caidas' => $entrevistado['n_caidas'],
                    'n_convivientes_3_meses' => $entrevistado['n_convivientes_3_meses'],
                    'id_investigador' => $entrevistado['id_investigador'],
                    'id_ciudad' => $entrevistado['id_ciudad'],
                    'id_nivel_educacional' => $entrevistado['id_nivel_educacional'],
                    'id_estado_civil' => $entrevistado['id_estado_civil'],
                    'id_tipo_convivencia' => $entrevistado['id_tipo_convivencia'],
                    'id_profesion' => $entrevistado['id_profesion']
                )
            );

            $response = $response->withStatus(200);
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

//Editar un entrevistados
$app->put('/entrevistados/{id}', function ($request, $response, $args) {

    $idEntrevistado = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $idEntrevistado
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
    if (!is_numeric($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    }

    //CAMPOS OPCIONALES
    $nCaidas = $putData['n_caidas'] ?? null;
    $nivelEducacional = $putData['id_nivel_educacional'] ?? null;
    $tipoConvivencia = $putData['id_tipo_convivencia'] ?? null;
    if (array_key_exists('nombre_profesion', $putData)) {
        $nombreProfesion = htmlspecialchars(ucfirst($putData['nombre_profesion']));
    } else {
        $nombreProfesion = null;
    }

    //Campos obligatorios
    if (!array_key_exists('nombre', $putData) || $putData['nombre'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('apellido', $putData) || $putData['apellido'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('sexo', $putData) || $putData['sexo'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Sexo is empty');
        $response = $response->withStatus(400);
    } else if (!array_key_exists('fecha_nacimiento', $putData) || $putData['fecha_nacimiento'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Fecha_nac is empty');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('nombre_ciudad', $putData) || $putData['nombre_ciudad'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_ciudad is empty');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('jubilado_legal', $putData) || $putData['jubilado_legal'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado legal is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($putData['jubilado_legal'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Jubilado_legal must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('caidas', $putData) || $putData['caidas'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($putData['caidas'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Caidas must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('id_estado_civil', $putData) || $putData['id_estado_civil'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil is empty');
        $response = $response->withStatus(400);

    } else if (!is_numeric($putData['id_estado_civil'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_estado_civil must be integer');
        $response = $response->withStatus(400);

    } else if (!array_key_exists('id_investigador', $putData) || $putData['id_investigador'] === "") {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putData['id_investigador'])) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_investigador must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Actualizar Entrevistado
        $object = new Entrevistado();
        $object->setId(htmlspecialchars($idEntrevistado));
        $object->setNombre(htmlspecialchars($putData['nombre']));
        $object->setApellido(htmlspecialchars($putData['apellido']));
        $object->setSexo(htmlspecialchars($putData['sexo']));
        $object->setFechaNac($putData['fecha_nacimiento']);
        $object->setNombreCiudad(htmlspecialchars(ucwords($putData['nombre_ciudad'])));
        $object->setJubiladoLegal(htmlspecialchars($putData['jubilado_legal']));
        $object->setCaidas(htmlspecialchars($putData['caidas']));
        $object->setNConvivientes(htmlspecialchars($putData['n_convivientes_3_meses']));
        $object->setIdInvestigador(htmlspecialchars($putData['id_investigador']));
        $object->setIdEstadoCivil(htmlspecialchars($putData['id_estado_civil']));

        //Opcionales
        $object->setNCaidas($nCaidas);
        $object->setIdNivelEducacional($nivelEducacional);
        $object->setIdTipoConvivencia($tipoConvivencia);
        $object->setNombreProfesion($nombreProfesion);

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
                    'jubilado_legal' => $entrevistado['jubilado_legal'],
                    'caidas' => $entrevistado['caidas'],
                    'n_caidas' => $entrevistado['n_caidas'],
                    'n_convivientes_3_meses' => $entrevistado['n_convivientes_3_meses'],
                    'id_investigador' => $entrevistado['id_investigador'],
                    'id_ciudad' => $entrevistado['id_ciudad'],
                    'id_nivel_educacional' => $entrevistado['id_nivel_educacional'],
                    'id_estado_civil' => $entrevistado['id_estado_civil'],
                    'id_tipo_convivencia' => $entrevistado['id_tipo_convivencia'],
                    'id_profesion' => $entrevistado['id_profesion'],
                    'update_time' => $entrevistado['update_time']
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

//Eliminar un usuario
$app->delete('/entrevistados/{id}', function ($request, $response, $args) {

    $idEntrevistado = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/entrevistados/" . $idEntrevistado
        )
    );

    if (!isset($idEntrevistado) || !is_numeric($idEntrevistado) || empty($idEntrevistado)) {

        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        $object = new Entrevistado();
        $object->setId($idEntrevistado);
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
