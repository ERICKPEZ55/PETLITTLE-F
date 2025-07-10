<<<<<<< HEAD
<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT nombre FROM especialidades ORDER BY nombre ASC");
    $especialidades = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($especialidades);
} catch (Exception $e) {
    echo json_encode([]);
}
=======
<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT nombre FROM especialidades ORDER BY nombre ASC");
    $especialidades = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($especialidades);
} catch (Exception $e) {
    echo json_encode([]);
}
>>>>>>> f09693777529f8988286f9f474767640441d8127
