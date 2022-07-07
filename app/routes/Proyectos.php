<?php

/**
 * Listado de proyectos del sistema
 */
$app->get('/proyectos', function ($request, $response, $args) {

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/proyectos"
        ),
        'data' => array()
    );

    if ($conn !== null) {

        $object = new Proyecto();
        $listado = $object->buscarTodos($conn);

        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'proyecto',
                'id' => $value['id'],
                'attributes' => array(
                    'id' => $value['id'],
                    'nombre' => $value['nombre']
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

/**
 * Crear proyecto
 */
$app->post('/proyectos', function ($request, $response, $args) {

    //Seccion link self
    $payload = array(
        'links' => array(
            'self' => '/proyectos'
        )
    );

    $data = $request->getParsedBody();

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    if (!array_key_exists('nombre', $data) || $data['nombre'] === "") {
        $payload = ErrorJsonHandler::lanzarError($payload, 400, 'Invalid parameter', 'Nombre is empty');
        $response = $response->withStatus(400);
    } else if ($conn !== null) {

        $object = new Proyecto();
        $object->setNombre(htmlspecialchars($data['nombre']));

        $lastId = $object->agregar($conn);

        if (!$lastId) {
            $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Create problem', 'Create a new object has fail');
            $response = $response->withStatus(500);
        } else {
            $object->setId($lastId);
            $proyecto = $object->buscarProyecto($conn);

            //Formatear respuesta
            $payload['data'] = array(
                'type' => 'proyecto',
                'id' => $proyecto['id'],
                'attributes' => array(
                    'id' => $proyecto['id'],
                    'nombre' => $proyecto['nombre']
                )
            );

            $response = $response->withStatus(201);
        }
    } else {
        $payload = ErrorJsonHandler::lanzarError($payload, 500, 'Server problem', 'A connection problem ocurred with database');
        $response = $response->withStatus(500);
    }

    $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($payload);

    //Desconectar mysql
    $mysql_adapter->disconnect();

    return $response;

})->add(new JwtMiddleware());