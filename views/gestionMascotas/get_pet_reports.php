<?php

require_once '../../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');


$id_mascota = $_GET['id_mascota'] ?? null;

if (!$id_mascota) {
    // Si no se proporciona el ID de la mascota, devolver un error JSON
    echo json_encode(['success' => false, 'error' => 'ID de mascota no proporcionado.']);
    exit; // Terminar la ejecución del script
}

try {
   
    $stmt = $pdo->prepare("
        SELECT
            id_reporte,
            fecha_reporte,
            diagnostico
        FROM
            reportes_medicos
        WHERE
            id_mascota = :id_mascota
        ORDER BY
            fecha_reporte DESC
    ");
    $stmt->execute([':id_mascota' => $id_mascota]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los reportes en formato JSON
    echo json_encode(['success' => true, 'reports' => $reports]);

} catch (\PDOException $e) {
    // Si ocurre un error de base de datos, devolver un mensaje de error JSON
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>