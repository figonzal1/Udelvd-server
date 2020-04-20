<?php

//require '../../vendor/autoload.php';

use Kreait\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;

function enviarNotificacion($investigador)
{
    $factory = (new Firebase\Factory())
        ->withServiceAccount('../udelvd-server-credentials.json');

    $messaging = $factory->createMessaging();

    //Configuracion mensaje ANDROID
    $config = AndroidConfig::fromArray([
        'ttl' => '7200s',   // 1 Hora de expiracion para sismo verificado
        'priority' => 'HIGH'  //Prioridad HIGH
    ]);

    //Configuracion de data
    $data = [
        'titulo' => 'Solicitud de activacion de cuenta',
        'descripcion' => $investigador['nombre'] . ' ' . $investigador['apellido'] . ', email ' . $investigador['email'] . ', solicita activacion de cuenta.',
        'id' => $investigador['id']
    ];
    /*
    $data = [
        'titulo' => 'Solicitud de activacion de cuenta',
        'descripcion' => 'descripcion de emoticon'
    ];*/

    //Envio por tema
    $topic = 'RegistroInvestigador';
    $message = CloudMessage::withTarget('topic', $topic)
        ->withAndroidConfig($config)
        ->withData($data);

    try {
        $messaging->validate($message);
        $messaging->send($message);

        error_log("Enviando notificacion", 0);
    } catch (InvalidMessage $e) {
        error_log("Fail to send notification: " . $e->errors(), 0);
    }
}

//enviarNotificacion("");
