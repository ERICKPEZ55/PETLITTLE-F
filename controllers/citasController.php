<?php
session_start();
require_once '../configuracion/conexion.php';
$pdo = conexion();

// Mostrar errores para depuración (desactíalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Verifica si el usuario está autenticado
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$action = $_GET['action'] ?? '';

// Si viene por POST sin parámetro "action", se toma del JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($action)) {
    $json = json_decode(file_get_contents("php://input"), true);
    $action = $json['action'] ?? '';
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
        // Buscar el id_especialidad por nombre
        $stmt = $pdo->prepare("SELECT id_especialidad FROM especialidades WHERE nombre = ?");
        $stmt->execute([$json['especialidad']]);
        $id_especialidad = $stmt->fetchColumn();

        if (!$id_especialidad) {
            echo json_encode(['success' => false, 'message' => 'Especialidad no válida.']);
            exit;
        }

        // Actualizar la cita
        $stmt = $pdo->prepare("
            UPDATE citas 
            SET id_especialidad = ?, fecha_hora = ?
            WHERE id_cita = ? AND id_usuario = ?
        ");

        $ok = $stmt->execute([
            $id_especialidad,
            $json['fecha_hora'],
            $json['id_cita'],
            $id_usuario
        ]);

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
        $stmt = $pdo->prepare("DELETE FROM citas WHERE id_cita = ? AND id_usuario = ?");
        $ok = $stmt->execute([$_GET['id'], $id_usuario]);

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
