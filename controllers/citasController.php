<?php
session_start();
require_once '../configuracion/conexion.php';
$pdo = conexion();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';

header('Content-Type: application/json');

// Verifica si el usuario está autenticado
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$action = $_GET['action'] ?? null;

// Si el método es POST y no hay parámetro GET "action", intenta extraerlo del JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = json_decode(file_get_contents("php://input"), true);
    if (!$action) {
        $action = $json['action'] ?? null;
    }
}

// === LISTAR CITAS DEL USUARIO ACTUAL ===
if ($action === 'listar') {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                c.id_cita,
                u.nombre AS nombre_usuario,
                u.correo,
                u.telefono,
                m.nombre AS nombre_mascota,
                e.nombre AS nombre_especialidad,
                c.fecha_hora
            FROM citas c
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
            INNER JOIN mascotas m ON c.id_mascota = m.id_mascota
            INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            WHERE c.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al listar: ' . $e->getMessage()]);
    }
    exit;
}

// === OBTENER UNA CITA POR ID ===
if ($action === 'obtener' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                c.id_cita,
                u.nombre AS nombre_usuario,
                u.correo,
                u.telefono,
                m.nombre AS nombre_mascota,
                e.nombre AS nombre_especialidad,
                c.fecha_hora
            FROM citas c
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
            INNER JOIN mascotas m ON c.id_mascota = m.id_mascota
            INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            WHERE c.id_cita = ? AND c.id_usuario = ?
        ");
        $stmt->execute([$_GET['id'], $id_usuario]);
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $cita ? json_encode($cita) : json_encode(['success' => false, 'message' => 'Cita no encontrada']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener: ' . $e->getMessage()]);
    }
    exit;
}

// === ACTUALIZAR CITA ===
if ($action === 'actualizar') {
    $json = json_decode(file_get_contents("php://input"), true);

    if (!isset($json['id_cita'], $json['especialidad'], $json['fecha_hora'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    try {
        // Obtener cita actual para comparar
        $stmtAnt = $pdo->prepare("SELECT e.nombre AS especialidad, c.fecha_hora, m.nombre AS mascota FROM citas c INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad INNER JOIN mascotas m ON c.id_mascota = m.id_mascota WHERE c.id_cita = ? AND c.id_usuario = ?");
        $stmtAnt->execute([$json['id_cita'], $id_usuario]);
        $anterior = $stmtAnt->fetch();

        // Buscar el id_especialidad por nombre
        $stmt = $pdo->prepare("SELECT id_especialidad FROM especialidades WHERE nombre = ?");
        $stmt->execute([$json['especialidad']]);
        $id_especialidad = $stmt->fetchColumn();

        if (!$id_especialidad) {
            echo json_encode(['success' => false, 'message' => 'Especialidad no válida.']);
            exit;
        }

        // Actualizar la cita
        $stmt = $pdo->prepare("UPDATE citas SET id_especialidad = ?, fecha_hora = ? WHERE id_cita = ? AND id_usuario = ?");
        $ok = $stmt->execute([$id_especialidad, $json['fecha_hora'], $json['id_cita'], $id_usuario]);

        if ($ok) {
            // Enviar correo
            $stmtUser = $pdo->prepare("SELECT nombre, apellido, correo FROM usuarios WHERE id_usuario = ?");
            $stmtUser->execute([$id_usuario]);
            $usuario = $stmtUser->fetch();

            $stmtCita = $pdo->prepare("SELECT nombre FROM mascotas WHERE id_mascota = (SELECT id_mascota FROM citas WHERE id_cita = ?)");
            $stmtCita->execute([$json['id_cita']]);
            $mascota = $stmtCita->fetch();

            $nombreUsuario = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $correoUsuario = $usuario['correo'];
            $nombreMascota = $mascota['nombre'];
            $fecha = date('d/m/Y', strtotime($json['fecha_hora']));
            $hora = date('H:i', strtotime($json['fecha_hora']));

            $fechaAnt = date('d/m/Y', strtotime($anterior['fecha_hora']));
            $horaAnt = date('H:i', strtotime($anterior['fecha_hora']));

            $mail = new PHPMailer(true);
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
            $mail->addAddress($correoUsuario, $nombreUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Cita Editada en PetLittle';
            $mail->Body = "
                <h2>Hola $nombreUsuario</h2>
                <p>Tu cita ha sido actualizada:</p>
                <h4>Datos anteriores:</h4>
                <ul>
                    <li><strong>Especialidad:</strong> {$anterior['especialidad']}</li>
                    <li><strong>Fecha:</strong> $fechaAnt</li>
                    <li><strong>Hora:</strong> $horaAnt</li>
                    <li><strong>Mascota:</strong> {$anterior['mascota']}</li>
                </ul>
                <h4>Datos nuevos:</h4>
                <ul>
                    <li><strong>Especialidad:</strong> {$json['especialidad']}</li>
                    <li><strong>Fecha:</strong> $fecha</li>
                    <li><strong>Hora:</strong> $hora</li>
                    <li><strong>Mascota:</strong> $nombreMascota</li>
                </ul>
                <p>Gracias por utilizar PetLittle 🐾</p>
            ";
            $mail->send();
        }

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Cita actualizada correctamente' : 'Error al actualizar la cita'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error en actualización: ' . $e->getMessage()]);
    }
    exit;
}

// === ELIMINAR CITA ===
if ($action === 'eliminar' && isset($_GET['id'])) {
    try {
        $stmtCita = $pdo->prepare("SELECT c.fecha_hora, e.nombre AS especialidad, m.nombre AS mascota
            FROM citas c
            JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            JOIN mascotas m ON c.id_mascota = m.id_mascota
            WHERE c.id_cita = ? AND c.id_usuario = ?");
        $stmtCita->execute([$_GET['id'], $id_usuario]);
        $cita = $stmtCita->fetch();

        $stmtUser = $pdo->prepare("SELECT nombre, apellido, correo FROM usuarios WHERE id_usuario = ?");
        $stmtUser->execute([$id_usuario]);
        $usuario = $stmtUser->fetch();

        $stmt = $pdo->prepare("DELETE FROM citas WHERE id_cita = ? AND id_usuario = ?");
        $ok = $stmt->execute([$_GET['id'], $id_usuario]);

        if ($ok && $cita) {
            $nombreUsuario = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $correoUsuario = $usuario['correo'];
            $fecha = date('d/m/Y', strtotime($cita['fecha_hora']));
            $hora = date('H:i', strtotime($cita['fecha_hora']));

            $mail = new PHPMailer(true);
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
            $mail->addAddress($correoUsuario, $nombreUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Cita Cancelada en PetLittle';
            $mail->Body = "
                <h2>Hola $nombreUsuario</h2>
                <p>Tu cita ha sido cancelada:</p>
                <ul>
                    <li><strong>Especialidad:</strong> {$cita['especialidad']}</li>
                    <li><strong>Fecha:</strong> $fecha</li>
                    <li><strong>Hora:</strong> $hora</li>
                    <li><strong>Mascota:</strong> {$cita['mascota']}</li>
                </ul>
                <p>Gracias por utilizar PetLittle 🐾</p>
            ";
            $mail->send();
        }

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Cita eliminada correctamente.' : 'No se pudo eliminar la cita.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    }
    exit;
}

// === ACCIÓN INVÁLIDA ===
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Acción inválida.']);
exit;
