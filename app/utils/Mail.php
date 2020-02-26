<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



function sendEmail($investigador, $dynamicLink, $idioma)
{

    $dotenv = Dotenv\Dotenv::create(__DIR__ . "../../../");
    $dotenv->load();

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'felipe.gonzalezalarcon94@gmail.com';   // SMTP username
        $mail->Password   = getenv('GOOGLE_PASSWORD_EMAIL');        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('felipe.gonzalezalarcon94@gmail.com', 'App - UDELVD');
        $mail->addAddress($investigador['email']);

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';                              // Set email format to HTML

        if ($idioma == "es") {
            $titulo = "Recuperación de cuenta";
            $saludo = "¡Hola " . $investigador['nombre'] . " " . $investigador['apellido'] . "!";
            $mensaje = "Parece que has olvidado tu contraseña. Para reestablecerla, haz click en el botón de abajo.";
            $footer = "Gracias,<br>
            Administración, App - Un Día en la Vida de ...";
            $boton = "Reestablecer contraseña";
        } else if ($idioma == "en") {
            $titulo = "Account recovery";
            $saludo = "Hello " . $investigador['nombre'] . " " . $investigador['apellido'] . "!";
            $mensaje = "You seem to have forgotten your password. To reset it, click the button below.";
            $footer = "Thanks,<br>
            Administration, App - A day in the life of ...";
            $boton = "Reset password";
        }

        $mail->Subject = $titulo;        //TODO: AGREGAR SOPORTE INGLES
        $mail->Body    =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="format-detection" content="telephone=no"> 
      <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
      <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
      
          <title>Reset your password</title>
      
          <style type="text/css"> 
      
              /* Some resets and issue fixes */
              #outlook a { padding:0; }
              body{ width:100% !important; -webkit-text; size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; }     
              .ReadMsgBody { width: 100%; }
              .ExternalClass {width:100%;} 
              .backgroundTable {margin:0 auto; padding:0; width:100% !important;} 
              table td {border-collapse: collapse;}
              .ExternalClass * {line-height: 115%;}           
              /* End reset */
      
      
              /* These are our tablet/medium screen media queries */
              @media screen and (max-width: 630px){
      
      
                  /* Display block allows us to stack elements */                      
                  *[class="mobile-column"] {display: block !important;} 
      
                  /* Some more stacking elements */
                  *[class="mob-column"] {float: none !important;width: 100% !important;}     
      
                  /* Hide stuff */
                  *[class="hide"] {display:none !important;}          
      
                  /* This sets elements to 100% width and fixes the height issues too, a god send */
                  *[class="100p"] {width:100% !important; height:auto !important; border:0 !important;}
                  *[class="100pNoPad"] {width:100% !important; height:auto !important; border:0 !important;padding:0 !important;}                    
      
                  /* For the 2x2 stack */         
                  *[class="condensed"] {padding-bottom:40px !important; display: block;}
      
                  /* Centers content on mobile */
                  *[class="center"] {text-align:center !important; width:100% !important; height:auto !important;}            
      
                  /* 100percent width section with 20px padding */
                  *[class="100pad"] {width:100% !important; padding:20px;} 
      
                  /* 100percent width section with 20px padding left & right */
                  *[class="100padleftright"] {width:100% !important; padding:0 20px 0 20px;} 
              }
      
              @media screen and (max-width: 300px){
                  /* 100percent width section with 20px padding top & bottom */
                  *[class="100padtopbottom"] {width:100% !important; padding:0px 0px 40px 0px; display: block; text-align: center !important;} 
              }
          </style>
      
      
      </head>
      
      <body style="padding:0; margin:0">
      
      <table border="0" cellpadding="0" cellspacing="0" style="margin: 0" width="100%">
        <tr>
          <td height="30"></td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="100p" style="border-radius: 8px; border: 1px solid #E2E5E7; overflow:hidden;">
              <tr>
                <td height="20"></td>
              </tr>    
              <tr>
                <td width="640" valign="top" class="100p">
                  <!-- Header -->
                  <table border="0" cellspacing="0" cellpadding="0" width="640" class="100p">
                    <!--<tr>
                      <td align="left" width="50%" class="100padtopbottom" style="padding-left: 20px">
                        <img alt="Logo" src="https://s3-us-west-2.amazonaws.com/descript-public/email/logo%402x.jpg" width="112" style="width: 100%; max-width: 112px; font-family: Arial, sans-serif; color: #ffffff; font-size: 20px; display: block; border: 0px;" border="0">
                      </td>
                    </tr>
                    -->
                    <!--<tr>
                      <td colspan="2" width="640" height="160" class="100p">
                        <img alt="Logo" src="https://cdn4.iconfinder.com/data/icons/outlines-business-web-optimization/256/1-74-512.png" width="640" style="width: 100%; max-width: 640px; font-family: Arial, sans-serif; color: #ffffff; font-size: 20px; display: block; border: 0px; margin-top:0px;" border="0">
                      </td>
                    </tr>-->
                    <tr>
                      <td colspan="2" align="left" valign="center" width="640" height="40" class="100p center" style="font-family: Arial, sans-serif; font-weight: bold; font-size:14px;padding: 0px 20px;">
                        <font face="Arial, sans-serif"><b>' . $saludo . '</b></font>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="center" width="640" class="100p" style="font-family: Arial, sans-serif; font-size:14px; padding: 0px 20px; line-height: 18px;">
                        <font face="Arial, sans-serif">
                            ' . $mensaje . '<br>
                            <br>
                            ' . $footer . '
                        </font>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" valign="center" width="640" height="20" class="100p center" style="font-family: Arial, sans-serif; font-weight: bold; font-size:1px;padding: 0px 20px;">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- Footer -->
              <tr>
                <td width="640" class="100p center" height="80" align="center" valign="top">
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" style="border-radius: 18px;" bgcolor="#fb8c00">
                        <a href="' . $dynamicLink . '" style="font-size: 14px; font-family: sans-serif; color: #ffffff; text-decoration: none; border-radius: 18px; padding: 5px 16px; border: 1px solid #fb8c00; display: inline-block; box-shadow: 0 2px 3px 0 rgba(0,0,0,0.10);">
                          <!--[if mso]> <![endif]-->
                          ' . $boton . '
                          <!--[if mso]> <![endif]-->
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
        </body>
      </html>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Failed to send email: " . $e->getMessage(), 0);
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
