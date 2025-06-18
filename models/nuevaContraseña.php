<?php
session_start();
class ConexionBD {
    private $host = "localhost";
    private $usuario = "root";
    private $clave = "";
    private $bd = "prueba";
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->bd);
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function cerrar() {
        $this->conexion->close();
    }
}

class RecuperarContrasena {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function actualizar($correo, $nueva, $confirmacion) {
        if ($nueva !== $confirmacion) {
            return "Las contraseñas no coinciden.";
        }
        $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena=? WHERE correo=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nuevaHash, $correo);

        if ($stmt->execute()) {
            return "ok";  // Devuelve un identificador para mostrar el botón
        } else {
            return "Error al actualizar: " . $stmt->error;
        }
    }
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_SESSION['correo_recuperacion'];
    $nueva = $_POST['nueva_contraseña'];
    $confirmacion = $_POST['confirmar_contraseña'];

    $db = new ConexionBD();
    $recuperar = new RecuperarContrasena($db->conexion);
    $resultado = $recuperar->actualizar($correo, $nueva, $confirmacion);
    $db->cerrar();

    if ($resultado === "ok") {
        $mensaje = "Contraseña actualizada correctamente. Ya puedes iniciar sesión.";
        $mostrarBoton = true;
    } else {
        $mensaje = $resultado;
        $mostrarBoton = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - PetLittle</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link rel="stylesheet" href="../assets/css/nuevaContraseña.css">
</head>
<body>
<div class="card">
    <h2>Cambiar Contraseña</h2>
    <p>Ingresa tu nueva contraseña</p>
    <form method="POST">
        <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
        <button type="submit">Actualizar</button>
    </form>

    <?php if (!empty($mensaje)) echo "<p class='mensaje'>$mensaje</p>"; ?>

    <?php if (isset($mostrarBoton) && $mostrarBoton): ?>
        <a href="login.php"><button>Inicia sesión</button></a>
    <?php endif; ?>
</div>
</body>
</html>
