<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_admin = $_SESSION['id_admin'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if (!$id_admin || !$nombre || !$apellido || !$correo || !$telefono) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE admins SET nombre = ?, apellido = ?, correo = ?, telefono = ? WHERE id_admin = ?");
    $success = $stmt->execute([$nombre, $apellido, $correo, $telefono, $id_admin]);

    header('Content-Type: application/json');
    echo json_encode(['status' => $success ? 'ok' : 'error']);
    exit;
}
