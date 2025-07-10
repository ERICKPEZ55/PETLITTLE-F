<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

// Obtener datos del cuerpo de la petición JSON
$data = json_decode(file_get_contents('php://input'), true);

$especialidad = $data['especialidad'] ?? '';
$fecha_hora = $data['fecha_hora'] ?? '';
$id_mascota = $data['id_mascota'] ?? '';
$id_usuario = $_SESSION['id_usuario'];

// Validaciones mínimas
if (!$especialidad || !$fecha_hora || !$id_mascota) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

// Buscar el id de la especialidad
$stmt = $pdo->prepare("SELECT id_especialidad FROM especialidades WHERE nombre = ?");
$stmt->execute([$especialidad]);
$especialidadRow = $stmt->fetch();

if (!$especialidadRow) {
    echo json_encode(['success' => false, 'error' => 'Especialidad inválida']);
    exit;
}

$id_especialidad = $especialidadRow['id_especialidad'];

// Validar si ya existe una cita para esa especialidad en esa fecha y hora
$stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE fecha_hora = ? AND id_especialidad = ?");
$stmt->execute([$fecha_hora, $id_especialidad]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'error' => 'Ya existe una cita de esta especialidad en esa fecha y hora.']);
    exit;
}

try {
    // Insertar la cita
    $stmt = $pdo->prepare("INSERT INTO citas (id_usuario, id_mascota, id_especialidad, fecha_hora) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $id_mascota, $id_especialidad, $fecha_hora]);

    // Obtener info del usuario y mascota
    $stmtUser = $pdo->prepare("SELECT nombre, apellido, correo FROM usuarios WHERE id_usuario = ?");
    $stmtUser->execute([$id_usuario]);
    $usuario = $stmtUser->fetch();

    $stmtMascota = $pdo->prepare("SELECT nombre FROM mascotas WHERE id_mascota = ?");
    $stmtMascota->execute([$id_mascota]);
    $mascota = $stmtMascota->fetch();

    $nombreUsuario = $usuario['nombre'] . ' ' . $usuario['apellido'];
    $correoUsuario = $usuario['correo'];
    $nombreMascota = $mascota['nombre'];
    $fecha = date('d/m/Y', strtotime($fecha_hora));
    $hora = date('H:i', strtotime($fecha_hora));

    // Enviar correo
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'petlittle.soporte@gmail.com';
    $mail->Password = 'covk nirl qowi eunt'; // 🔒 Reemplazar por clave de aplicación segura
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('petlittle.soporte@gmail.com', 'PetLittle');
    $mail->addAddress($correoUsuario, $nombreUsuario);

    $mail->isHTML(true);
    $mail->Subject = 'Cita Agendada en PetLittle';
    $mail->Body = "
        <h2>¡Hola $nombreUsuario!</h2>
        <p>Tu cita ha sido agendada con éxito:</p>
        <ul>
            <li><strong>Especialidad:</strong> $especialidad</li>
            <li><strong>Fecha:</strong> $fecha</li>
            <li><strong>Hora:</strong> $hora</li>
            <li><strong>Mascota:</strong> $nombreMascota</li>
        </ul>
        <p>Gracias por utilizar PetLittle 🐾</p>
    ";

    $mail->send();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("Error al guardar o enviar correo: {$e->getMessage()}");
    echo json_encode(['success' => false, 'error' => 'Error al guardar la cita o enviar el correo.']);
}
