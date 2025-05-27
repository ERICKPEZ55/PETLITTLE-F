<?php
session_start();
require_once 'SessionManager.php';

class Autenticador {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "prueba");
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function login($correo, $contraseña) {
        $sql = "SELECT * FROM datos WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if ($contraseña === $usuario['contraseña']) {
                // Inicia sesión usando SessionManager
                $session = new SessionManager();
                // Puedes usar 'id' si lo tienes en la tabla, o el correo como ID
                $session->login($usuario['correo'], $usuario['correo']);

                // Redirección según el correo
                if ($usuario['correo'] === 'juan.24@gmail.com') {
                    header("Location: agendamiento.php");
                } elseif ($usuario['correo'] === 'andres@gmail.com') {
                    header("Location: empleado.html");
                } elseif ($usuario['correo'] === "sofia@gmail.com") {
                    header("Location: vista.html");
                } else {
                    header("Location: ../Usuario/agendamiento.php");
                }
                exit();
            } else {
                return "Contraseña incorrecta.";
            }
        } else {
            return "Correo no registrado.";
        }
        $stmt->close();
    }

    public function __destruct() {
        $this->conexion->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $auth = new Autenticador();
    $error = $auth->login($correo, $contraseña);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PetLittle</title>
    <link rel="stylesheet" href="styleslogin.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1 class="logo">PetLittle</h1>
            <p>Bienvenido, ingresa tus datos para iniciar sesión.</p>

            <?php if (isset($error)): ?>
                <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form id="loginForm" action="login.php" method="post">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="correo" placeholder="Correo" required> 

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contraseña" placeholder="Contraseña" required>

                <p><a href="recuperar.html" style="color: #272A57;">¿Olvidaste tu contraseña?</a></p>

                <button type="submit">Ingresar</button>
            </form>

            <p class="register-link">¿No tienes cuenta?<br><a href="registro.php">Crear cuenta</a></p>
        </div>
        <div class="image-container">
            <img src="img/imagen.login.jpeg" alt="Perro y gato">
        </div>
    </div>

<script>
class LoginSessionManager {
    constructor(formId, emailId, passwordId) {
        this.form = document.getElementById(formId);
        this.emailInput = document.getElementById(emailId);
        this.passwordInput = document.getElementById(passwordId);

        this.autocompletar();
        this.configurarEventos();
    }

    autocompletar() {
        const correo = sessionStorage.getItem("correo");
        const contraseña = sessionStorage.getItem("contraseña");

        if (correo) this.emailInput.value = correo;
        if (contraseña) this.passwordInput.value = contraseña;
    }

    guardarEnSession() {
        sessionStorage.setItem("correo", this.emailInput.value);
        sessionStorage.setItem("contraseña", this.passwordInput.value);
    }

    limpiarSession() {
        sessionStorage.removeItem("correo");
        sessionStorage.removeItem("contraseña");
    }

    configurarEventos() {
        this.form.addEventListener("submit", () => this.guardarEnSession());
        window.addEventListener("beforeunload", () => this.limpiarSession());
    }
}

window.addEventListener("DOMContentLoaded", function () {
    new LoginSessionManager("loginForm", "email", "password");
});
</script>
</body>
</html>
