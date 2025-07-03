<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion(); 

$id_admin = $_SESSION['id_admin'] ?? null;

header('Content-Type: application/json');

if ($id_admin) {
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id_admin = ?");
    $success = $stmt->execute([$id_admin]);

    if ($success) {
        session_destroy();
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no identificado']);
}
