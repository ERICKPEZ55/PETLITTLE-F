<?php
// Cabeceras para permitir DELETE y respuestas JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

require_once(__DIR__ . '/../configuracion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Obtener el ID desde la URL
    parse_str(file_get_contents("php://input"), $_DELETE);

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $conexion = new mysqli("localhost", "root", "", "prueba");

        if ($conexion->connect_error) {
            echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM empleados WHERE id_empleado = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar']);
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
