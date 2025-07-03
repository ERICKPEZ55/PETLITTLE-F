<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

// Obtener la fecha desde la URL, o usar la de hoy si no viene
$fecha = $_GET['fecha'] ?? date('Y-m-d');

try {
    $sql = "
        SELECT 
            c.id_cita,
            m.nombre AS nombreM,
            u.nombre AS nombre,
            e.nombre AS especialidad,
            e.duracion,
            c.fecha_hora,
            c.estado
            FROM citas c
            JOIN mascotas m ON c.id_mascota = m.id_mascota
            JOIN usuarios u ON c.id_usuario = u.id_usuario
            JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            WHERE DATE(c.fecha_hora) = :fecha
            ORDER BY c.fecha_hora;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['fecha' => $fecha]);

    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($citas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las citas', 'detalles' => $e->getMessage()]);
}
