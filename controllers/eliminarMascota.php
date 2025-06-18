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

            // Eliminar imagen del servidor (si existe)
            $ruta_imagen = __DIR__ . '/../assets/img/mascotas/' . $imagen;
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }

            echo "<script>alert('Mascota eliminada exitosamente'); window.location.href = '../views/usuario/misMascotas.php';</script>";
        } else {
            echo "<script>alert('Mascota no encontrada o no autorizada.'); window.location.href = '../views/usuario/misMascotas.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID de mascota no proporcionado.'); window.location.href = '../views/usuario/misMascotas.php';</script>";
}
