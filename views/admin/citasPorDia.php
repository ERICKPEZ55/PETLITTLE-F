<?php
header('Content-Type: application/json');
require __DIR__ . '/../../configuracion/conexion.php';
$conn = conexion();

if (!isset($_GET['fecha'])) {
    echo json_encode(['error' => 'Fecha no enviada']);
    exit;
}

$fecha = $_GET['fecha'];

try {
    $stmt = $conn->prepare("SELECT c.id_cita, TIME(c.fecha_hora) AS hora, m.nombre AS mascota, u.nombre AS propietario
                            FROM citas c
                            JOIN mascotas m ON c.id_mascota = m.id_mascota
                            JOIN usuarios u ON m.id_usuario = u.id_usuario
                            WHERE DATE(c.fecha_hora) = :fecha AND c.estado != 'Cancelada'");
    $stmt->execute(['fecha' => $fecha]);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultado);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
