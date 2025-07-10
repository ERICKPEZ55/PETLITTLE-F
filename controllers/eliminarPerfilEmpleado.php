<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');
$pdo = conexion(); 

$id_empleado = $_SESSION['id_empleado'] ?? null;

if ($id_empleado) {
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id_empleado = ?");
    $success = $stmt->execute([$id_empleado]);

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
