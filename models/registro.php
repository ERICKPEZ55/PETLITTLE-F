<?php
session_start();

// Capturar el error guardado en sesión (si existe) y luego eliminarlo para que no aparezca siempre
$error = '';
if (isset($_SESSION['error_registro'])) {
    $error = $_SESSION['error_registro'];
    unset($_SESSION['error_registro']);
}

// Capturar si el registro fue exitoso
$registro_exitoso = false;
if (isset($_SESSION['registro_exitoso'])) {
    $registro_exitoso = true;
    unset($_SESSION['registro_exitoso']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrarse - PetLittle</title>
    <link rel="stylesheet" href="../assets/css/stylesRegistro.css" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1 class="logo">PetLittle</h1>
            <p>Bienvenido. Por favor, ingrese sus datos para completar el registro.</p>

            <form id="registerForm" action="../controllers/authcontrollers.php" method="post">
                <label for="username">Nombre:</label>
                <input
                    name="nombre"
                    type="text"
                    id="username"
                    placeholder="Nombre"
                    required
                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                    title="Solo letras y espacios."
                />

                <label for="lastname">Apellido:</label>
                <input
                    name="apellido"
                    type="text"
                    id="lastname"
                    placeholder="Apellido"
                    required
                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                    title="Solo letras y espacios."
                />

                <label for="email">Correo:</label>
                <input name="correo" type="email" id="email" placeholder="Correo" required />

                <label for="number">Teléfono:</label>
                <input
                    name="telefono"
                    type="tel"
                    id="number"
                    placeholder="Teléfono"
                    required
                    pattern="\d{10}"
                    maxlength="10"
                    title="Debe contener exactamente 10 dígitos."
                />

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

    <script type="text/javascript">
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const user = document.getElementById('username').value.trim();
            const lastname = document.getElementById('lastname').value.trim();
            const email = document.getElementById('email').value.trim();
            const number = document.getElementById('number').value.trim();
            const pass = document.getElementById('password').value.trim();

            const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
            const phoneRegex = /^\d{10}$/;

            if (user === '' || lastname === '' || email === '' || number === '' || pass === '') {
                alert('Faltan datos por rellenar');
                e.preventDefault();
                return;
            }

            if (!nameRegex.test(user)) {
                alert('El nombre solo debe contener letras y espacios');
                e.preventDefault();
                return;
            }

            if (!nameRegex.test(lastname)) {
                alert('El apellido solo debe contener letras y espacios');
                e.preventDefault();
                return;
            }

            if (!phoneRegex.test(number)) {
                alert('El teléfono debe tener exactamente 10 dígitos numéricos');
                e.preventDefault();
                return;
            }
        });

        <?php if ($error): ?>
            alert(<?php echo json_encode($error); ?>);
        <?php endif; ?>

        <?php if ($registro_exitoso): ?>
            alert("¡Registro exitoso!");
            window.location.href = "../models/login.php";
        <?php endif; ?>
    </script>
</body>
</html>
