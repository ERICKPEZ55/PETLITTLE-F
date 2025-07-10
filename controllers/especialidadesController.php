<?php
require_once __DIR__ . '/../configuracion/conexion.php';

header('Content-Type: application/json');
$pdo = conexion();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    if ($action === 'listar') {
        $stmt = $pdo->query("SELECT id_especialidad, nombre, imagen FROM especialidades");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        exit;
    }

    if ($action === 'crear') {
        $nombre = $_POST['nombre'] ?? '';
        if (empty($nombre)) {
            throw new Exception('El nombre de la especialidad es obligatorio');
        }

        $imagen = '';
        if (!empty($_FILES['imagen']['name'])) {
            $filename = 'ico' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME))) . '.png';
            $ruta = __DIR__ . '/../assets/img/' . $filename;
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
                throw new Exception('No se pudo subir la imagen de la especialidad');
            }
            $imagen = $filename;
        }

        $stmt = $pdo->prepare("INSERT INTO especialidades (nombre, imagen) VALUES (?, ?)");
        $stmt->execute([$nombre, $imagen]);
        echo json_encode(['status' => 'success', 'message' => 'Especialidad creada con éxito']);
        exit;
    }

    if ($action === 'actualizar') {
        $id = $_POST['id_especialidad'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        if (!$id || empty($nombre)) {
            throw new Exception('Faltan datos para actualizar la especialidad');
        }

        // Obtener imagen actual
        $stmt = $pdo->prepare("SELECT imagen FROM especialidades WHERE id_especialidad = ?");
        $stmt->execute([$id]);
        $old = $stmt->fetch();

        $imagen = $old['imagen'] ?? '';

        if (!empty($_FILES['imagen']['name'])) {
            $filename = 'ico' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME))) . '.png';
            $ruta = __DIR__ . '/../assets/img/' . $filename;
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
                throw new Exception('Error al subir la nueva imagen de la especialidad');
            }
            $imagen = $filename;
        }

        $stmt = $pdo->prepare("UPDATE especialidades SET nombre = ?, imagen = ? WHERE id_especialidad = ?");
        $stmt->execute([$nombre, $imagen, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Especialidad actualizada correctamente']);
        exit;
    }

    if ($action === 'eliminar') {
        $id = $_GET['id'] ?? null;

        if ($id) {
            // Validar si hay citas asociadas a esa especialidad
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE id_especialidad = ?");
            $stmt->execute([$id]);
            $total = $stmt->fetchColumn();

            if ($total > 0) {
                echo json_encode([
                    'status' => 'warning',
                    'message' => 'No se puede eliminar la especialidad porque tiene citas asociadas.'
                ]);
                exit;
            }

            // Si no hay citas, se puede eliminar
            $stmt = $pdo->prepare("DELETE FROM especialidades WHERE id_especialidad = ?");
            $stmt->execute([$id]);

            echo json_encode([
                'status' => 'success',
                'message' => 'Especialidad eliminada correctamente.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID inválido o no proporcionado para eliminar.'
            ]);
        }
        exit;
    }

    throw new Exception('Acción no válida o no proporcionada');
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}
