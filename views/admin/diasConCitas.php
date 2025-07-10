<<<<<<< HEAD
<?php
header('Content-Type: application/json');
require __DIR__ . '/../../configuracion/conexion.php';
$conn = conexion();

// Obtenemos mes y año desde GET
$mes = $_GET['mes'] ?? null;
$anio = $_GET['anio'] ?? null;

if (!$mes || !$anio) {
    echo json_encode(['error' => 'Mes o año no enviados']);
    exit;
}

// Formateamos mes con cero inicial
$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

// Rango de fechas del mes
$inicio = "$anio-$mes-01";
$fin = date("Y-m-t", strtotime($inicio)); // Último día del mes

try {
    $stmt = $conn->prepare("SELECT DISTINCT DATE(fecha_hora) AS fecha
                            FROM citas
                            WHERE fecha_hora BETWEEN :inicio AND :fin
                              AND estado != 'Cancelada'");
    $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
    $fechas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($fechas); // ejemplo: ["2025-07-08", "2025-07-12"]
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
=======
<?php
header('Content-Type: application/json');
require __DIR__ . '/../../configuracion/conexion.php';
$conn = conexion();

// Obtenemos mes y año desde GET
$mes = $_GET['mes'] ?? null;
$anio = $_GET['anio'] ?? null;

if (!$mes || !$anio) {
    echo json_encode(['error' => 'Mes o año no enviados']);
    exit;
}

// Formateamos mes con cero inicial
$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

// Rango de fechas del mes
$inicio = "$anio-$mes-01";
$fin = date("Y-m-t", strtotime($inicio)); // Último día del mes

try {
    $stmt = $conn->prepare("SELECT DISTINCT DATE(fecha_hora) AS fecha
                            FROM citas
                            WHERE fecha_hora BETWEEN :inicio AND :fin
                              AND estado != 'Cancelada'");
    $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
    $fechas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($fechas); // ejemplo: ["2025-07-08", "2025-07-12"]
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
>>>>>>> f09693777529f8988286f9f474767640441d8127
