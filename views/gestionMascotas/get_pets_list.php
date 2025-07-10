<?php
// get_pets_list.php
require_once '../../configuracion/conexion.php';
$pdo = conexion(); 
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT id_mascota, nombre FROM mascotas ORDER BY nombre ASC");
    $stmt->execute();
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200); // Todo salió bien
    echo json_encode(['success' => true, 'pets' => $pets]);

} catch (\PDOException $e) {
    error_log("Error en get_pets_list.php: " . $e->getMessage());

    http_response_code(500); // Error interno del servidor
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
