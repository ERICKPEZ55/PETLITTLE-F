<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if (!$id_usuario || !$nombre || !$apellido || !$correo || !$telefono) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, correo=?, telefono=?, fecha_actualizacion=NOW() WHERE id_usuario=?");
    $success = $stmt->execute([$nombre, $apellido, $correo, $telefono, $id_usuario]);

    header('Content-Type: application/json');
    echo json_encode(['status' => $success ? 'ok' : 'error']);
}
