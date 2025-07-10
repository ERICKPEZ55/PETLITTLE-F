<<<<<<< HEAD
<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'citasPorMes') {
    $stmt = $pdo->query("
        SELECT MONTH(fecha_hora) AS mes, COUNT(*) AS total
        FROM citas
        GROUP BY MONTH(fecha_hora)
        ORDER BY mes
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'estadoCitas') {
    $stmt = $pdo->query("
        SELECT estado, COUNT(*) AS total
        FROM citas
        GROUP BY estado
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'tiposCitas') {
    $stmt = $pdo->query("
        SELECT e.nombre AS especialidad, COUNT(*) AS total
        FROM citas c
        INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        GROUP BY e.nombre
        ORDER BY total DESC
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción inválida']);
=======
<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'citasPorMes') {
    $stmt = $pdo->query("
        SELECT MONTH(fecha_hora) AS mes, COUNT(*) AS total
        FROM citas
        GROUP BY MONTH(fecha_hora)
        ORDER BY mes
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'estadoCitas') {
    $stmt = $pdo->query("
        SELECT estado, COUNT(*) AS total
        FROM citas
        GROUP BY estado
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'tiposCitas') {
    $stmt = $pdo->query("
        SELECT e.nombre AS especialidad, COUNT(*) AS total
        FROM citas c
        INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        GROUP BY e.nombre
        ORDER BY total DESC
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción inválida']);
>>>>>>> f09693777529f8988286f9f474767640441d8127
