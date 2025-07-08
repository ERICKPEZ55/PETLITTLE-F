<?php
header('Content-Type: application/json');
require_once('../configuracion/conexion.php');

$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID faltante o datos no recibidos.'
    ]);
    exit;
}

$id = $datos['id'];
$nombre = $datos['nombre'] ?? null;
$apellido = $datos['apellido'] ?? null;
$especialidad = $datos['especialidad'] ?? null;
$telefono = $datos['telefono'] ?? null;
$usuario = $datos['usuario'] ?? null;

try {
    $pdo = conexion();
    $campos = [];
    $valores = [];

    if ($nombre !== null) {
        $campos[] = "nombre = ?";
        $valores[] = $nombre;
    }
    if ($apellido !== null) {
        $campos[] = "apellido = ?";
        $valores[] = $apellido;
    }
    if ($especialidad !== null) {
        $campos[] = "especialidad = ?";
        $valores[] = $especialidad;
    }
    if ($telefono !== null) {
        $campos[] = "telefono = ?";
        $valores[] = $telefono;
    }
    if ($usuario !== null) {
        $campos[] = "usuario = ?";
        $valores[] = $usuario;
    }

    if (empty($campos)) {
        echo json_encode([
            'success' => false,
            'message' => 'No se proporcionaron campos para actualizar.'
        ]);
        exit;
    }

    $valores[] = $id;

    $sql = "UPDATE empleados SET " . implode(', ', $campos) . " WHERE id_empleado = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($valores);

    echo json_encode([
        'success' => true,
        'message' => 'Empleado actualizado correctamente.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
