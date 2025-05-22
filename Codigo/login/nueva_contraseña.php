<?php
class ConexionBD {
    private $host = "localhost";
    private $usuario = "root";
    private $clave = "123456";
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

        // Encriptar contraseña antes de guardar
        $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);

        $sql = "UPDATE datos SET contraseña=? WHERE correo=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nuevaHash, $correo);

        if ($stmt->execute()) {
            return "Contraseña actualizada correctamente. Ya puedes iniciar sesión.";
        } else {
            return "Error al actualizar: " . $stmt->error;
        }
    }
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $nueva = $_POST['nueva_contraseña'];
    $confirmacion = $_POST['confirmar_contraseña'];

    $db = new ConexionBD();
    $recuperar = new RecuperarContrasena($db->conexion);
    $mensaje = $recuperar->actualizar($correo, $nueva, $confirmacion);
    $db->cerrar();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña-PetLittle</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #7bb5e3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 10px;
            color: #1c2141;
        }

        p {
            color: #333;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background-color: #252551;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 0 #181833;
        }

        button:hover {
            background-color: #333369;
        }

        .mensaje {
            margin-top: 20px;
            font-weight: bold;
            color: #1c2141;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Cambiar Contraseña</h2>
    <p>Ingresa tu correo y una nueva contraseña</p>
    <form method="POST" action="">
        <input type="email" name="correo" placeholder="Tu correo" required>
        <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
        <button type="submit">Actualizar</button>
    </form>

    <?php if (isset($mensaje)) echo "<p class='mensaje'>$mensaje</p>"; ?>
</div>

</body>