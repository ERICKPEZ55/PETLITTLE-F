<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';
require_once('../configuracion/conexion.php'); 

$email = $_POST['email'];
$link = $_POST['link'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'petlittle.soporte@gmail.com';
    $mail->Password = 'covk nirl qowi eunt'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Aquí agregamos la codificación correcta
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('petlittle.soporte@gmail.com', 'PetLittle');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Recupera tu contraseña en PetLittle';
    $template = file_get_contents('../views/login/emailTemplate.html'); 
    $body = str_replace('{{link}}', $link, $template); 
    $mail->Body = $body;


    $mail->send();
    echo "Correo enviado correctamente.";
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
?>
