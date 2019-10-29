<?php

/**
 * Clase encargada en configurar atritbuto 'Error' de respuestas json
 */
class ErrorJsonHandler {

    public static function lanzarError($payload,$status_code,$title,$detail){
        $payload['error'] = array(
            'status' => $status_code,
            'title' => $title,
            'detail' => $detail
        );
        return $payload;
    }
}
?>