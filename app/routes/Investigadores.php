<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

require_once("app/config/MysqlAdapter.php");
require_once("app/class/Investigador.php");
require_once("app/validators/IntegerValidator.php");



/**
 * GET /investigadores: Listado de investigadores del sistema
 */
$app->get('/investigadores', function ($request, $response, $args) {

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

        if (!$listado) {
            $response->withStatus(500);
            return $response;
        }
        if (empty($investigador)) {
            $payload['data'] = array();
        }
        //Preparar respuesta
        foreach ($listado as $key => $value) {

            array_push(
                $payload['data'],
                array(
                    'type' => 'investigadores',
                    'id' => $value['id'],
                    'attirbutes' => array(
                        'nombre' => $value['nombre'],
                        'apellido' => $value['apellido'],
                        'email' => $value['email'],
                        'id_rol' => $value['id_rol']
                    )
                )
            );
        }
    } else {
        $payload['error'] = array(
            'status' => 500,
            'title' => 'Server connection problem',
            'detail' => 'A connection problem ocurred with database'
        );
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
$app->get('/investigadores/{id:[0-9]+}', function ($request, $response, $args) {

    $id_investigador = $args['id'];

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/investigadores/" . $id_investigador
        )
    );

    if ($conn != null) {

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
                    'id_rol' => $investigador['id_rol']
                )
            );
        }
    } else {
        $payload['error'] = array(
            'status' => 500,
            'title' => 'Server connection problem',
            'detail' => 'A connection problem ocurred with database'
        );
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


    $payload = array(
        'links' => array(
            'self' => '/investigadores'
        )
    );

    $data = $request->getParsedBody();

    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Direccion email no valida";
    }

    //Conectar BD
    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    //Buscar investigadores
    $object = new Investigador();
    $object->setNombre(htmlentities($data['nombre']));
    $object->setApellido(htmlentities($data['apellido']));
    $object->setEmail(htmlentities($data['email']));


    return $response;
});
