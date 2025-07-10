<?php
session_start();  // No lo olvides si usas id_usuario para validar
require_once '../configuracion/conexion.php';
$pdo = conexion();

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no especificado']);
    exit;
}

$idOrden = intval($_GET['id']);

$sql = "SELECT 
            o.id_orden,
            m.nombre AS mascota,
            m.raza,
            u.nombre AS propietario,
            o.tipo_muestra,
            o.pruebas,
            o.laboratorio_destino,
            o.urgencia,
            o.notas,
            o.fecha_creacion
        FROM ordenes_laboratorio o
        INNER JOIN mascotas m ON o.id_mascota = m.id_mascota
        INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE o.id_orden = :idOrden";

$stmt = $pdo->prepare($sql);
$stmt->execute(['idOrden' => $idOrden]);
$orden = $stmt->fetch(PDO::FETCH_ASSOC);

if ($orden) {
    header('Content-Type: application/json'); // 👈 MUY IMPORTANTE
    echo json_encode($orden);
} else {
    echo json_encode(['error' => 'Orden no encontrada']);
}
?>
