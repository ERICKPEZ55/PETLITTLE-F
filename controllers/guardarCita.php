<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion();

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

try {
    $stmt = $pdo->prepare("INSERT INTO citas (id_usuario, id_mascota, id_especialidad, fecha_hora) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $id_mascota, $id_especialidad, $fecha_hora]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al guardar la cita.']);
}
?>
