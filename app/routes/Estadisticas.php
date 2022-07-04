<?php

//* Listado de visualizaciones de estadisticas en el sistema
/*
$app->get('/estadisticas', function ($request, $response, $args) {

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $payload = array(
        'links' => array(
            'self' => "/estadisticas"
        ),
        'data' => array()
    );

    if ($conn !== null) {
        //Buscar estadisticas
        $object = new Estadisticas();
        $listado = $object->buscarEstadisticas($conn);

        //Preparar respuesta
        foreach ($listado as $value) {

            $payload['data'][] = array(
                'type' => 'estadisticas',
                'id' => $value['id'],
                'attributes' => array(
                    'url' => $value['url'],
                    'nombre_es' => $value['nombre_es'],
                    'nombre_en' => $value['nombre_en'],
                    'pin_pass' => $value['pin_pass']
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
*/

$app->get("/estadisticas[/{params:.*}]", function ($request, $response, $args) {

    $payload = array(
        'links' => array(
            'self' => "/estadisticas"
        ),
        'data' => array()
    );

    $mysql_adapter = new MysqlAdapter();
    $conn = $mysql_adapter->connect();

    $params = explode('/', $args['params']);

    //investigador/{id_investigador}/emoticon/{id_emoticon}/genero/{f|m}
    foreach ($params as $i => $iValue) {

        if ($iValue === "investigador") {
            $idInvestigador = $params[$i + 1];
        }

        if ($iValue === "emoticon") {
            $idEmoticon = $params[$i + 1];
        }

        if ($iValue === "genero") {
            $letraGenero = $params[$i + 1];
        }
    }

    $idInvestigador = $idInvestigador ?? null;
    $idEmoticon = $idEmoticon ?? null;
    $letraGenero = $letraGenero ?? null;

    if ($idInvestigador !== null) {
        $payload['links']['self'] .= "/investigador/" . $idInvestigador;
    }
    if ($idEmoticon !== null) {
        $payload['links']['self'] .= "/emoticon/" . $idEmoticon;
    }
    if ($letraGenero !== null) {
        $payload['links']['self'] .= "/genero/" . $letraGenero;
    }

    if ($conn !== null) {

        //ENTREVISTADOS POR GENERO
        if ($idInvestigador !== null) {
            $investigador = new Investigador();
            $investigador->setId($idInvestigador);
            $proyectoInvestigador = $investigador->buscarInvestigadorPorId($conn)['proyecto'];
        } else {
            $proyectoInvestigador = null;
        }

        $entrevistado = new Entrevistado();
        $listadoPorGenero = $entrevistado->entrevistadosPorGenero($conn, $proyectoInvestigador, $idEmoticon, $letraGenero);

        $nEntrevistados = count($listadoPorGenero);
        $nEventos = 0;
        $totalFemenino = 0;
        $totalMasculino = 0;
        $totalOtro = 0;

        foreach ($listadoPorGenero as $value) {

            if ($value['sexo'] === "Femenino") {
                ++$totalFemenino;
            } else if ($value['sexo'] === "Masculino") {
                ++$totalMasculino;
            } else {
                ++$totalOtro;
            }
            $nEventos += $value['n_eventos'];
        }

        //EVENTOS POR EMOTICON
        $evento = new Evento();
        $listadoPorEmoticon = $evento->eventosPorEmoticon($conn, $proyectoInvestigador, $idEmoticon, $letraGenero);

        $totalFelicidad = 0;
        $totalTristeza = 0;
        $totalMiedo = 0;
        $totalEnojo = 0;

        foreach ($listadoPorEmoticon as $value) {
            if (str_contains($value['descripcion_es'], "felicidad")) {
                $totalFelicidad = (int)$value['n_emoticones'];
            }

            if (str_contains($value['descripcion_es'], "tristeza")) {
                $totalTristeza = (int)$value['n_emoticones'];
            }

            if (str_contains($value['descripcion_es'], "miedo")) {
                $totalMiedo = (int)$value['n_emoticones'];
            }

            if (str_contains($value['descripcion_es'], "enojo")) {
                $totalEnojo = (int)$value['n_emoticones'];
            }
        }

        $payload['data'][0] = array(
            'type' => 'estadisticas',
            'attributes' => array(
                'general' => array(
                    'n_entrevistados' => $nEntrevistados,
                    'n_eventos' => $nEventos,
                ),
                'entrevistados_por_genero' => array(
                    'total_femenino' => $totalFemenino,
                    'total_masculino' => $totalMasculino,
                    'total_otros' => $totalOtro
                ),
                'eventos_por_emoticon' => array(
                    'felicidad' => $totalFelicidad,
                    'tristeza' => $totalTristeza,
                    'miedo' => $totalMiedo,
                    'enojo' => $totalEnojo
                )
            )
        );

        $payload['data'][0]['attributes']['eventos_para_estadisticas'] = array();

        //EVENTOS PARA ESTADISTICAS
        $eventosParaEstadisticas = $evento->eventosParaEstadisticas($conn, $proyectoInvestigador, $idEmoticon, $letraGenero);

        foreach ($eventosParaEstadisticas as $iValue) {

            $payload['data'][0]['attributes']['eventos_para_estadisticas'][] = array(
                'nombre' => $iValue['nombre'],
                'apellido' => $iValue['apellido'],
                'accion' => $iValue['nombre_accion'],
                'hora_evento' => $iValue['hora_evento'],
                'justificacion' => $iValue['justificacion'],
                'url' => $iValue['url'],
            );
        }

        $payload = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);

        return $response;
    }
});
