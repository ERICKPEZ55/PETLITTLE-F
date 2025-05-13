<?php

$servidor = "localhost";
$usuario = "root";
$clave = "123456";
$bd = "prueba";


$conexion = mysqli_connect($servidor, $usuario, "", $bd);


if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}


if (isset($_POST['enviar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        die("Error: El nombre solo puede contener letras y espacios.");
    }

    if (!preg_match("/^\d{10}$/", $telefono)) {
        die("Error: El teléfono debe contener exactamente 10 dígitos.");
    }

   
    $insertar = "INSERT INTO datos (nombre, correo, telefono, contraseña) 
                 VALUES ('$nombre', '$correo', '$telefono', '$contraseña')";

    if (mysqli_query($conexion, $insertar)) {
        echo "<script>alert('Registro exitoso'); window.location.href='login.php';</script>";
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - PetLittle</title>
    <link rel="stylesheet" href="stylesregistro.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1 class="logo">PetLittle</h1>
            <p>Bienvenido. Por favor, ingrese sus datos para completar el registro.</p>
            <form id="registerForm" method="post">
                <label for="username">Nombre:</label>
                <input name="nombre" type="text" id="username" placeholder="Nombre" required
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras y espacios.">

                <label for="email">Correo:</label>
                <input name="correo" type="email" id="email" placeholder="Correo" required>

                <label for="number">Teléfono:</label>
                <input name="telefono" type="tel" id="number" placeholder="Teléfono" required
                       pattern="\d{10}" maxlength="10" title="Debe contener exactamente 10 dígitos.">

                <label for="password">Contraseña:</label>
                <input name="contraseña" type="password" id="password" placeholder="Contraseña" required>

                <button type="submit" name="enviar">Registrarme</button>
            </form>
            <p class="register-link"><a href="login.php">Iniciar Sesión</a></p>
        </div>
        <div class="image-container">
            <img src="img/imagen.register.jpeg" alt="Perro y gato">
        </div>
    </div>

    <script type="text/javascript">
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const user = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const number = document.getElementById('number').value.trim();
        const pass = document.getElementById('password').value.trim();

        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
        const phoneRegex = /^\d{10}$/;

        if (user === '' || email === '' || number === '' || pass === '') {
            alert('Faltan datos por rellenar');
            e.preventDefault();
            return;
        }

        if (!nameRegex.test(user)) {
            alert('El nombre solo debe contener letras y espacios');
            e.preventDefault();
            return;
        }

        if (!phoneRegex.test(number)) {
            alert('El teléfono debe tener exactamente 10 dígitos numéricos');
            e.preventDefault();
            return;
        }
    });
    </script>
</body>
</html>
