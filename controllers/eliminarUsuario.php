<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion(); 

$id_usuario = $_SESSION['id_usuario'] ?? null;

if ($id_usuario) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $success = $stmt->execute([$id_usuario]);

    if ($success) {
        session_destroy();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no identificado']);
}
