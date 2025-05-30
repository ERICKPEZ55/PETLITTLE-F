<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';
require_once('../configuracion/conexion.php'); 

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Conexión
    $pdo = conexion(); // Asegúrate de que la función conexion() esté bien definida

    // Verificar si el correo existe en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM datos WHERE correo = :correo");
    $stmt->bindParam(':correo', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        $mensaje = "El correo ingresado no está registrado.";
    } else {
        $codigo = rand(100000, 999999);

        $_SESSION["codigo_recuperacion"] = $codigo;
        $_SESSION["correo_recuperacion"] = $email;

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

            $mail->setFrom('petlittle.soporte@gmail.com', 'PetLittle');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Código de recuperación - PetLittle';
            $template = file_get_contents('../views/login/email_template.html');
            $body = str_replace('{{codigo}}', $codigo, $template);
            $mail->Body = $body;

            $mail->send();

            header("Location: verificar.php");
            exit;
        } catch (Exception $e) {
            $mensaje = "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - PetLittle</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link rel="stylesheet" href="../assets/css/stylesrecuperar.css">
</head>
<body>
<div class="container">
    <h2>Recuperar Contraseña</h2>
    <p>Ingresa tu correo y te enviaremos un código.</p>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Tu correo" required>
        <button type="submit">Enviar</button>
    </form>
    <?php if (!empty($mensaje)) echo "<p style='color: red; font-weight: bold;'>$mensaje</p>"; ?>
</div>
</body>
</html>
