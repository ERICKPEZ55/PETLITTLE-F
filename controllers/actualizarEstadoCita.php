<?php
require_once '../configuracion/conexion.php';
$pdo = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = $_POST['id_cita'] ?? null;
    $estado = $_POST['estado'] ?? null;

    if ($idCita && in_array($estado, ['Asistió', 'No asistió', 'Cancelada'])) {
        $sql = "UPDATE citas SET estado = :estado WHERE id_cita = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'estado' => $estado,
            'id' => $idCita
        ]);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
