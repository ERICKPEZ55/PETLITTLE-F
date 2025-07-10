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
    $sexo       = $_POST['sexo'];
    $id_usuario = $_SESSION['id_usuario'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $imagen_tmp    = $_FILES['imagen']['tmp_name'];

        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp','jfif'];
        $extension = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));

        if (!in_array($extension, $extensiones_permitidas)) {
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'Formato de imagen no permitido. Solo se permiten: jpg, jpeg, png, gif, webp, jfif.'
            ];
            header("Location: ../views/usuario/misMascotas.php");
            exit;
        }

        $carpeta_destino = __DIR__ . '/../assets/img/mascotas/';
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        $nombre_unico = uniqid('mascota_') . '.' . $extension;
        $ruta_imagen = $carpeta_destino . $nombre_unico;

        if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
            try {
                $pdo = conexion();
                $sql = "INSERT INTO mascotas (nombre, raza, sexo, id_usuario, imagen)
                        VALUES (:nombre, :raza, :sexo, :id_usuario, :imagen)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre'     => $nombre,
                    ':raza'       => $raza,
                    ':sexo'       => $sexo,
                    ':id_usuario' => $id_usuario,
                    ':imagen'     => $nombre_unico
                ]);

                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Mascota registrada exitosamente.'
                ];
                header("Location: ../views/usuario/misMascotas.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['alerta'] = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al guardar la mascota: ' . $e->getMessage()
                ];
                header("Location: ../views/usuario/misMascotas.php");
                exit;
            }
        } else {
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'Error al mover la imagen al servidor.'
            ];
            header("Location: ../views/usuario/misMascotas.php");
            exit;
        }
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'error',
            'mensaje' => 'Debe subir una imagen para registrar la mascota.'
        ];
        header("Location: ../views/usuario/misMascotas.php");
        exit;
    }
}
?>
