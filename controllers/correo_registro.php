<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';


function enviarCorreoRegistro($correo, $nombre) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'petlittle.soporte@gmail.com';
        $mail->Password = 'covk nirl qowi eunt'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom('petlittle.soporte@gmail.com', 'PetLittle');
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Registro exitoso en PetLittle';
        $mail->Body = "
            <h2>¡Hola $nombre!</h2>
            <p>Tu registro en <strong>PetLittle</strong> fue exitoso.</p>
            <p>Ya puedes ingresar al sistema y empezar a agendar tus citas.</p>
            <br>
            <p>Gracias por confiar en nosotros 🐾</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar correo de registro: {$mail->ErrorInfo}");
    }
}
