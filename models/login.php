<?php
session_start();

// Inicializamos las variables para evitar el warning
$error = '';
$registro_exitoso = false;

// Comprobamos si existen mensajes de sesión
if (isset($_SESSION['error_login'])) {
    $error = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}

if (isset($_SESSION['registro_exitoso'])) {
    $registro_exitoso = true;
    unset($_SESSION['registro_exitoso']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PetLittle</title>
    <link rel="stylesheet" href="../assets/css/stylesLogin.css" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1 class="logo">PetLittle</h1>
            <p>Bienvenido, ingresa tus datos para iniciar sesión.</p>

            <form id="loginForm" action="../controllers/authControllers.php" method="post">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="correo" placeholder="Correo" required />

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contrasena" placeholder="Contraseña" required />

                <p><a href="../models/recuperar.php" style="color: #272A57;">¿Olvidaste tu contraseña?</a></p>

                <button type="submit" name="login">Ingresar</button>
            </form>

            <p class="register-link">¿No tienes cuenta?<br><a href="../models/registro.php">Crear cuenta</a></p>
        </div>
        <div class="image-container">
            <img src="../assets/img/imagen.login.jpeg" alt="Perro y gato" />
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
            const contrasena = sessionStorage.getItem("contrasena");

            if (correo) this.emailInput.value = correo;
            if (contrasena) this.passwordInput.value = contrasena;
        }

        guardarEnSession() {
            sessionStorage.setItem("correo", this.emailInput.value);
            sessionStorage.setItem("contrasena", this.passwordInput.value);
        }

        limpiarSession() {
            sessionStorage.removeItem("correo");
            sessionStorage.removeItem("contrasena");
        }

        configurarEventos() {
            this.form.addEventListener("submit", () => this.guardarEnSession());
            window.addEventListener("beforeunload", () => this.limpiarSession());
        }
    }

        window.addEventListener("DOMContentLoaded", function () {
        new LoginSessionManager("loginForm", "email", "password");

        <?php if ($registro_exitoso): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: 'Por favor inicia sesión.',
                confirmButtonColor: '#3085d6'
            });
        <?php endif; ?>

        <?php if ($error): ?>
            <?php if ($error === 'correo_no_encontrado'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Correo no registrado',
                    text: 'El correo que ingresaste no está registrado.'
                });
            <?php elseif ($error === 'contrasena_incorrecta'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    text: 'La contraseña que ingresaste no es correcta.'
                });
            <?php else: ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error de inicio de sesión',
                    text: 'Por favor verifica tus datos.'
                });
            <?php endif; ?>
        <?php endif; ?>
    });

    </script>
</body>
</html>
