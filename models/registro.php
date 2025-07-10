<?php
session_start();

// Capturar errores o éxito del registro
$error = '';
if (isset($_SESSION['error_registro'])) {
    $error = $_SESSION['error_registro'];
    unset($_SESSION['error_registro']);
}

$registro_exitoso = false;
if (isset($_SESSION['registro_exitoso'])) {
    $registro_exitoso = true;
    unset($_SESSION['registro_exitoso']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrarse - PetLittle</title>
    <link rel="stylesheet" href="../assets/css/stylesRegistro.css" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1 class="logo">PetLittle</h1>
            <p>Bienvenido. Por favor, ingrese sus datos para completar el registro.</p>

            <form id="registerForm" action="../controllers/authcontrollers.php" method="post">
                <label for="username">Nombre:</label>
                <input name="nombre" type="text" id="username" placeholder="Nombre" required title="Solo letras y espacios." />

                <label for="lastname">Apellido:</label>
                <input name="apellido" type="text" id="lastname" placeholder="Apellido" required title="Solo letras y espacios." />

                <label for="email">Correo:</label>
                <input name="correo" type="email" id="email" placeholder="Correo" required />

                <label for="number">Teléfono:</label>
                <input name="telefono" type="tel" id="number" placeholder="Teléfono" required maxlength="10" title="Debe contener exactamente 10 dígitos." />

                <label for="password">Contraseña:</label>
                <input name="contrasena" type="password" id="password" placeholder="Contraseña" required />

                <button type="submit" name="registrar">Registrarme</button>
            </form>

            <p class="register-link"><a href="../models/login.php">Iniciar Sesión</a></p>
        </div>
        <div class="image-container">
            <img src="../assets/img/imagen.register.jpeg" alt="Perro y gato" />
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('registerForm');

        form.addEventListener('submit', function (e) {
            const user = document.getElementById('username').value.trim();
            const lastname = document.getElementById('lastname').value.trim();
            const email = document.getElementById('email').value.trim();
            const number = document.getElementById('number').value.trim();
            const pass = document.getElementById('password').value.trim();

            const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
            const phoneRegex = /^\d{10}$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (user === '' || lastname === '' || email === '' || number === '' || pass === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Faltan datos por rellenar'
                });
                return;
            }

            if (!nameRegex.test(user)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Nombre inválido',
                    text: 'El nombre solo debe contener letras y espacios.'
                });
                return;
            }

            if (!nameRegex.test(lastname)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Apellido inválido',
                    text: 'El apellido solo debe contener letras y espacios.'
                });
                return;
            }

            if (!phoneRegex.test(number)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Teléfono inválido',
                    text: 'El teléfono debe tener exactamente 10 dígitos numéricos.'
                });
                return;
            }

            if (!passwordRegex.test(pass)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña inválida',
                    text: 'Debe tener al menos 8 caracteres, incluyendo una letra y un número.'
                });
                return;
            }
        });

        <?php if ($error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar',
                text: <?php echo json_encode($error); ?>
            });
        <?php endif; ?>

        <?php if ($registro_exitoso): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: 'Tu cuenta ha sido creada correctamente.',
                confirmButtonText: 'Iniciar sesión',
                confirmButtonColor: '#6EACDA'
            }).then(() => {
                window.location.href = "../models/login.php";
            });
        <?php endif; ?>
    });
    </script>
</body>
</html>
