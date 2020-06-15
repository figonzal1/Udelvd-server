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
        'ttl' => '7200s',   // 2 horas de expiracion si el dispositiv no se conecta a internet
        'priority' => 'HIGH'  //Prioridad HIGH
    ]);

    //Configuracion de data
    $data = [
        'titulo_es' => 'Solicitud de activación de cuenta',
        'titulo_en' => 'Account activation request',
        'descripcion_es' => $investigador['nombre'] . ' ' . $investigador['apellido'] . ', email ' . $investigador['email'] . ', solicita activación de cuenta.',
        'descripcion_en' => $investigador['nombre'] . ' ' . $investigador['apellido'] . ', email ' . $investigador['email'] . ', request account activation.',
        'id' => $investigador['id']
    ];

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
