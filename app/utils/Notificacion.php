<?php /** @noinspection ForgottenDebugOutputInspection */

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;

function enviarNotificacion($investigador): void
{
    $factory = (new Factory)
        ->withServiceAccount('../udelvd-server-credentials.json');

    $messaging = $factory->createMessaging();

    //Configuracion mensaje ANDROID
    $config = AndroidConfig::fromArray([
        'ttl' => '7200s',   // 2 horas de expiracion si el dispositiv no se conecta a internet
        'priority' => 'HIGH'  //Prioridad HIGH
    ])->withNormalMessagePriority();

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

        error_log("Enviando notificacion");
    } catch (MessagingException|FirebaseException $e) {
        error_log("Fail to send notification: " . $e->errors());
    }
}
