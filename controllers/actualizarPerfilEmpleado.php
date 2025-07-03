<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_empleado = $_SESSION['id_empleado'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if (!$id_empleado || !$nombre || !$apellido || !$usuario || !$telefono) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE empleados SET nombre = ?, apellido = ?, usuario = ?, telefono = ? WHERE id_empleado = ?");
    $success = $stmt->execute([$nombre, $apellido, $usuario, $telefono, $id_empleado]);

    header('Content-Type: application/json');
    echo json_encode(['status' => $success ? 'ok' : 'error']);
    exit;
}
