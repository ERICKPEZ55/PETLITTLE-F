<?php
require_once(__DIR__ . '/../configuracion/conexion.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;

    if ($id_usuario) {
        try {
            $pdo = conexion();

            // Primero eliminamos las mascotas asociadas (si aplica)
            $stmtMascotas = $pdo->prepare("DELETE FROM mascotas WHERE id_usuario = ?");
            $stmtMascotas->execute([$id_usuario]);

            // Luego eliminamos el usuario
            $stmtUsuario = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmtUsuario->execute([$id_usuario]);

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error en la base de datos: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no recibido.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
?>
