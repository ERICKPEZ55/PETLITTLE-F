<?php
session_start();
require_once(__DIR__ . '/../configuracion/conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /PetLittle/models/login.php");
    exit;
}

if (isset($_POST['registrar'])) {
    $nombre     = trim($_POST['nombre']);
    $raza       = trim($_POST['raza']);
    $genero     = $_POST['genero'];
    $id_usuario = $_SESSION['id_usuario'];

    // Comprobamos si se subió una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $imagen_tmp    = $_FILES['imagen']['tmp_name'];

        // Asegurar que la extensión sea válida
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp','jfif'];
        $extension = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));

        if (!in_array($extension, $extensiones_permitidas)) {
            echo "<script>alert('Formato de imagen no permitido.'); window.history.back();</script>";
            exit;
        }

        // Crear carpeta si no existe
        $carpeta_destino = __DIR__ . '/../assets/img/mascotas/';
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        // Evitar que se sobrescriban archivos
        $nombre_unico = uniqid('mascota_') . '.' . $extension;
        $ruta_imagen = $carpeta_destino . $nombre_unico;

        if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
            try {
                $pdo = conexion();
                $sql = "INSERT INTO mascotas (nombre, raza, genero, id_usuario, imagen)
                        VALUES (:nombre, :raza, :genero, :id_usuario, :imagen)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre'     => $nombre,
                    ':raza'       => $raza,
                    ':genero'     => $genero,
                    ':id_usuario' => $id_usuario,
                    ':imagen'     => $nombre_unico 
                ]);

                echo "<script>alert('Mascota registrada exitosamente'); window.location.href='../views/usuario/misMascotas.php';</script>";
            } catch (PDOException $e) {
                echo "Error al guardar: " . $e->getMessage();
            }
        } else {
            echo "<script>alert('Error al mover la imagen al servidor'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Debe subir una imagen'); window.history.back();</script>";
    }
}
?>
