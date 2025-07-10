<<<<<<< HEAD
<?php
require_once '../configuracion/conexion.php';

header('Content-Type: application/json');

try {
    $pdo = conexion();

    $stmt = $pdo->prepare("SELECT id_especialidad, nombre FROM especialidades");
    $stmt->execute();

    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($especialidades);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener especialidades: ' . $e->getMessage()]);
}
=======
<?php
require_once '../configuracion/conexion.php';

header('Content-Type: application/json');

try {
    $pdo = conexion();

    $stmt = $pdo->prepare("SELECT id_especialidad, nombre FROM especialidades");
    $stmt->execute();

    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($especialidades);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener especialidades: ' . $e->getMessage()]);
}
>>>>>>> f09693777529f8988286f9f474767640441d8127
