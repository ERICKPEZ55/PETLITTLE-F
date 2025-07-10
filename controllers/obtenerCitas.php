<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            c.id_cita,
            u.nombre AS nombre_usuario,
            u.correo,
            u.telefono,
            m.nombre AS nombre_mascota,
            e.nombre AS especialidad,
            c.fecha_hora
        FROM citas c
        INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
        INNER JOIN mascotas m ON c.id_mascota = m.id_mascota
        INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        ORDER BY c.fecha_hora ASC
    ");

    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($citas);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener citas: ' . $e->getMessage()
    ]);
}
