<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /PetLittle/models/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_mascota = $_GET['id'];

    try {
        $pdo = conexion();

        // Obtener nombre del archivo de imagen
        $stmtImg = $pdo->prepare("SELECT imagen FROM mascotas WHERE id_mascota = :id AND id_usuario = :usuario");
        $stmtImg->execute([
            ':id' => $id_mascota,
            ':usuario' => $_SESSION['id_usuario']
        ]);
        $mascota = $stmtImg->fetch();

        if ($mascota) {
            $imagen = $mascota['imagen'];

            // Eliminar de la base de datos
            $stmt = $pdo->prepare("DELETE FROM mascotas WHERE id_mascota = :id AND id_usuario = :usuario");
            $stmt->execute([
                ':id' => $id_mascota,
                ':usuario' => $_SESSION['id_usuario']
            ]);

            // Eliminar imagen del servidor
            $ruta_imagen = __DIR__ . '/../assets/img/mascotas/' . $imagen;
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }

            $_SESSION['alerta'] = [
                'tipo' => 'success',
                'mensaje' => 'Mascota eliminada exitosamente.'
            ];
            header("Location: ../views/usuario/misMascotas.php");
            exit;

        } else {
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'Mascota no encontrada o no tienes permiso para eliminarla.'
            ];
            header("Location: ../views/usuario/misMascotas.php");
            exit;
        }

    } catch (PDOException $e) {
        // Verificar si el error es por restricción de clave foránea
        if ($e->getCode() == 23000) {
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'No puedes eliminar esta mascota porque tiene citas activas asociadas.'
            ];
        } else {
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'Ocurrió un error al intentar eliminar la mascota.'
            ];
        }

        header("Location: ../views/usuario/misMascotas.php");
        exit;
    }

} else {
    $_SESSION['alerta'] = [
        'tipo' => 'error',
        'mensaje' => 'ID de mascota no proporcionado.'
    ];
    header("Location: ../views/usuario/misMascotas.php");
    exit;
}
