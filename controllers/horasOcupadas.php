<?php
require_once('../configuracion/conexion.php');
header('Content-Type: application/json');

$especialidad = $_GET['especialidad'] ?? '';
$fecha = $_GET['fecha'] ?? '';

if (!$especialidad || !$fecha) {
    echo json_encode([]);
    exit;
}

try {
    $pdo = conexion();

    // Obtener el ID de la especialidad por su nombre
    $stmt = $pdo->prepare("SELECT id_especialidad FROM especialidades WHERE nombre = ?");
    $stmt->execute([$especialidad]);
    $idEspecialidad = $stmt->fetchColumn();

    if (!$idEspecialidad) {
        echo json_encode([]);
        exit;
    }

    // Buscar las horas ocupadas de esa especialidad y fecha
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(fecha_hora, '%H:%i') AS hora 
                           FROM citas 
                           WHERE id_especialidad = ? AND DATE(fecha_hora) = ?");
    $stmt->execute([$idEspecialidad, $fecha]);
    $horas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($horas);
} catch (PDOException $e) {
    echo json_encode([]);
}
