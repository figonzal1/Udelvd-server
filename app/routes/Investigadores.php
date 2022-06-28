<?php

//* Listado de investigadores del sistema
use Dotenv\Dotenv;

$app->get('/investigadores', function ($request, $response, $args) {

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores"
        ),
        'data' => array()
    );

    if ($conn !== null) {
        //Buscar investigadores
        $object = new Investigador();

        $listado = $object->buscarTodos($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'investigadores',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $value['nombre'],
                    'apellido' => $value['apellido'],
                    'email' => $value['email'],
                    'id_rol' => $value['id_rol'],
                    'activado' => $value['activado']
                ),
                'relationships' => array(
                    'rol' => array(
                        'data' => array(
                            'id' => $value['id_rol'],
                            'nombre' => $value['nombre_rol']
                        )
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
    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();
    return $response;
})->add(new JwtMiddleware());

//* REGISTRO INVESTIGADOR
$app->post('/investigadores', function ($request, $response, $args) {


    $dotenv = Dotenv::createImmutable(__DIR__ . "../../../");
    $dotenv->load();

    //? CONFIGURACION DE ACTIVACION AUTOMATICA
    $activacionEnv = $_ENV["AUTO_ACTIVACION"]; //true | false

    if ($activacionEnv === 'true') {
        $autoActivacion = 1;
    } else {
        $autoActivacion = 0;
    }

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
    if (empty($data['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['email'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['nombre_rol'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre_rol is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is malformed');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar investigador
        $object = new Investigador();

        $object->setNombre(htmlspecialchars(ucfirst($data['nombre'])));
        $object->setApellido(htmlspecialchars(ucfirst($data['apellido'])));
        $object->setEmail(htmlspecialchars(strtolower($data['email'])));
        $object->setNombreRol(htmlspecialchars(ucfirst($data['nombre_rol'])));
        $object->setPassword($data['password']);
        $object->setActivado($autoActivacion);

        $investigador = $object->buscarInvestigadorPorEmail($conn);

        if ($investigador !== null) {
            //Lanzar error de email
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Email problem', 'Email already exists');
            $response = $response->withStatus(500);
        } else {
            //insertar investigador
            $lastid = $object->agregar($conn);

            //Insert error
            if (!$lastid) {
                $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new researcher has fail');
                $response = $response->withStatus(500);
            } else {

                $object->setId($lastid);
                $investigador = $object->buscarInvestigadorPorId($conn);

                //FCM NOTIFICATION
                if ($activacionEnv === "false") {
                    enviarNotificacion($investigador);
                }

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
                    ),
                    'relationships' => array(
                        'rol' => array(
                            'data' => array(
                                'id' => $investigador['id_rol'],
                                'nombre' => $investigador['nombre_rol']
                            )
                        )
                    )
                );

                $response = $response->withStatus(201);
            }
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
});

//* RESETEAR CONTRASEÑA INVESTIGADOR
$app->put('/investigadores/resetear', function ($request, $response, $args) {


    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/investigadores/resetear"
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $patchData = array();
    parse_str($data, $patchData);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (empty($patchData['email'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is empty');
        $response = $response->withStatus(400);
    } else if (!filter_var($patchData['email'], FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Enter valid Email');
        $response = $response->withStatus(400);
    } else if (empty($patchData['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {
        //Buscar investigador por email

        $object = new Investigador();
        $object->setEmail(htmlspecialchars($patchData['email']));
        $investigador = $object->buscarInvestigadorPorEmail($conn);

        //Si investigador no existe
        if (empty($investigador)) {
            $payload['data'] = array();
            $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Email problem', 'Email does not exist');
            $response = $response->withStatus(400);
        } else {
            $object = new Investigador();
            $object->setPassword($patchData['password']);
            $object->setEmail($patchData['email']);

            $reset = $object->resetPassword($conn);

            //Insert error
            if (!$reset) {
                $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Reset problem', 'Reset password researcher has fail');
                $response = $response->withStatus(500);
            } else {
                $payload['reset'] = array(
                    'type' => 'reset',
                    'status' => 'password reseted'
                );
                $response = $response->withStatus(201);
            }
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
});

//* Logear en el sistema
$app->post('/investigadores/login', function ($request, $response, $args) {

    $payload = array(
        'links' => array(
            'self' => '/investigadores/login'
        )
    );

    //Obtener parametros post
    $data = $request->getParsedBody();

    //Conectar mysql
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * Validaciond parametros
     */
    if (empty($data['email'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is empty');
        $response = $response->withStatus(400);
    } else if (empty($data['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Realizar login
        $object = new Investigador();
        $object->setEmail(htmlspecialchars(strtolower($data['email'])));
        $object->setPasswordRaw($data['password']);

        $investigador = $object->buscarInvestigadorPorEmail($conn);

        //Si el correo NO existe
        if (!$investigador) {
            //Lanzar error de email
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Email problem', 'Email does not exist');
            $response = $response->withStatus(500);
        } //Si el usuario no esta activado
        else if ($investigador['activado'] === 0) {
            //Lanzar error de activacion
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Account problem', 'Account deactivated');
            $response = $response->withStatus(500);
        } //Si el correo existe
        else {

            //Hacer login
            $status = $object->login($conn);

            //Si la pass no es la misma
            if (!$status) {
                $payload = ErrorJsonHandler::lanzarError($payload, 403, 'Login problem', 'Please check your credentials');
                $response = $response->withStatus(403);
            } else {

                //Generar token
                $jwt = new Jwt();
                $token = $jwt->generarToken($investigador['id']);

                //Formatar respuesta
                $payload['login'] = array(
                    'type' => 'login',
                    'status' => 'Correct',
                    'token' => $token
                );

                //Formatear respuesta
                $payload['data'] = array(
                    'type' => 'investigadores',
                    'id' => $investigador['id'],
                    'attributes' => array(
                        'nombre' => $investigador['nombre'],
                        'apellido' => $investigador['apellido'],
                        'email' => $investigador['email'],
                        'id_rol' => $investigador['id_rol'],
                        'activado' => $investigador['activado'],
                        'create_time' => $investigador['create_time']
                    ),
                    'relationships' => array(
                        'rol' => array(
                            'data' => array(
                                'id' => $investigador['id_rol'],
                                'nombre' => $investigador['nombre_rol']
                            )
                        )
                    )
                );

                $response = $response->withStatus(200);
            }
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
});

//* Obtener listado de investigadores para admin
$app->get('/investigadores/pagina/{n_pag}/id_admin/{id_admin}', function ($request, $response, $args) {

    $idAdmin = $args['id_admin'];
    $nPag = $args['n_pag'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/pagina/" . $nPag . "/id_admin/" . $idAdmin
        ),
        'data' => array()
    );

    if (empty($idAdmin) || !is_numeric($idAdmin)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'id admin must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar investigadores
        $object = new Investigador();
        $object->setId($idAdmin);

        $listado = $object->buscarPagina($conn, $nPag);

        $conteo = $object->contarInvestigadores($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'investigadores',
                'id' => $value['id'],
                'attributes' => array(
                    'nombre' => $value['nombre'],
                    'apellido' => $value['apellido'],
                    'email' => $value['email'],
                    'id_rol' => $value['id_rol'],
                    'activado' => $value['activado'],
                    'create_time' => $value['create_time']
                ),
                'relationships' => array(
                    'rol' => array(
                        'data' => array(
                            'id' => $value['id_rol'],
                            'nombre' => $value['nombre_rol']
                        )
                    )
                )
            );
        }

        $payload['investigadores'] = array(
            'data' => array(
                'n_investigadores' => $conteo
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

//* Obtener investigador segun id
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

    if (empty($id_investigador) || !is_numeric($id_investigador)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Buscar investigadores
        $object = new Investigador();
        $object->setId($id_investigador);
        $investigador = $object->buscarInvestigadorPorId($conn);

        //Si investigador no existe
        if (empty($investigador)) {
            $payload['data'] = array();
        } //Si el investigador existe
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
                ),
                'relationships' => array(
                    'rol' => array(
                        'data' => array(
                            'id' => $investigador['id_rol'],
                            'nombre' => $investigador['nombre_rol']
                        )
                    )
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

//* Editar un investigador
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
    $putData = array();
    parse_str($data, $putData);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    /**
     * VALIDACION PARAMETROS
     */
    if (!is_numeric($id_investigador)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id must be integer');
        $response = $response->withStatus(400);
    } else if (!filter_var($putData['email'], FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is malformed');
        $response = $response->withStatus(400);
    } else if (empty($putData['nombre'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['apellido'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Apellido is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['id_rol'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol is empty');
        $response = $response->withStatus(400);
    } else if (empty($putData['password'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Password is empty');
        $response = $response->withStatus(400);
    } else if (!is_numeric($putData['id_rol'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Id_rol must be integer');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar investigador
        $object = new Investigador();
        $object->setId(htmlspecialchars($id_investigador));
        $object->setNombre(htmlspecialchars($putData['nombre']));
        $object->setApellido(htmlspecialchars($putData['apellido']));
        $object->setEmail(htmlspecialchars($putData['email']));
        $object->setIdRol(htmlspecialchars($putData['id_rol']));
        $object->setPassword($putData['password']);

        //Actualizar investigador
        $actualizar = $object->actualizar($conn);

        //UPDATE error
        if (!$actualizar) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Update problem', 'Update a object has fail');
            $response = $response->withStatus(500);
        } else {

            $investigador = $object->buscarInvestigadorPorId($conn);


            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'id_rol' => $investigador['id_rol'],
                    'activado' => $investigador['activado'],
                    'update_time' => $investigador['update_time']
                ),
                'relationships' => array(
                    'rol' => array(
                        'data' => array(
                            'id' => $investigador['id_rol'],
                            'nombre' => $investigador['nombre_rol']
                        )
                    )
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

//* RECUPERAR CUENTA
$app->post('/investigadores/recuperar/{email}/idioma/{idioma}', function ($request, $response, $args) {

    $idioma = $args['idioma'];
    $email = $args['email'];

    //Conectar mysql
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/recuperar/" . $email . "/idioma/" . $idioma
        )
    );

    if (empty($email)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Email is empty');
        $response = $response->withStatus(400);
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Enter valid Email');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {
        //Buscar investigador por email

        $object = new Investigador();
        $object->setEmail(htmlspecialchars($email));
        $investigador = $object->buscarInvestigadorPorEmail($conn);

        //Si investigador no existe
        if (empty($investigador)) {
            $payload['data'] = array();
            $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Email problem', 'Email does not exist');
            $response = $response->withStatus(400);
        } else {

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'email' => $investigador['email'],
                )
            );

            $dynamicLink = crearDynamicLink();

            if ($dynamicLink) {
                $email = new EmailService();
                $status = $email->sendEmailRecuperar($investigador, $dynamicLink, $idioma);

                if (!$status) {
                    $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Send mail problem', 'Email with dynamic link has not been send');
                    $response = $response->withStatus(500);
                } else {
                    $payload['recovery'] = array(
                        'type' => 'recovery',
                        'status' => 'email sended',
                        //*SOLO DEBUG*//
                        'dynamicLink' => $dynamicLink
                    );

                    $response = $response->withStatus(200);
                }
            } else {
                $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Dynamic link', 'Dynamic link has not been created');
                $response = $response->withStatus(500);
            }
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
});

//* Activar - Desactivar Cuenta 
$app->patch('/investigadores/{id}/activar', function ($request, $response, $args) {

    $idInvestigador = $args['id'];

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $idInvestigador . "/activar"
        )
    );

    //Obtener parametros put
    $data = $request->getBody()->getContents();
    $patchData = array();
    parse_str($data, $patchData);

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!isset($patchData['activado'])) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Activado is empty');
        $response = $response->withStatus(400);
    } else if ($patchData['activado'] !== 0 && $patchData['activado'] !== 1) {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Activado must be 1 or 0');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        //Agregar investigador
        $object = new Investigador();
        $object->setId($idInvestigador);
        $object->setActivado(htmlspecialchars($patchData['activado']));

        //insertar investigador
        $activado = $object->activar($conn);

        //Insert error
        if (!$activado) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Activate problem', 'Activate a researcher has fail');
            $response = $response->withStatus(500);
        } else {

            $investigador = $object->buscarInvestigadorPorId($conn);

            if ($patchData['activado'] === 1) {
                $email = new EmailService();
                $email->sendEmailActivation($investigador);
            }

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'investigadores',
                'id' => $investigador['id'],
                'attributes' => array(
                    'nombre' => $investigador['nombre'],
                    'apellido' => $investigador['apellido'],
                    'email' => $investigador['email'],
                    'activado' => $investigador['activado']
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

//! Probablemente no será implementado
//* Eliminar un investigador
/*$app->delete('/investigadores/{id}', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador
        )
    );

    if (!isset($id_investigador) || empty($id_investigador) || !is_numeric($id_investigador)) {
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
})->add(new JwtMiddleware());*/
