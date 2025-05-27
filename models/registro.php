<?php
class RegistroUsuario {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "123456", "prueba");
        if ($this->conexion->connect_error) {
            die("Error al conectar a la base de datos: " . $this->conexion->connect_error);
        }
    }

    public function registrar($nombre, $apellido, $correo, $telefono, $contraseña) {
        // Validaciones del lado del servidor
        if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
            die("Error: El nombre solo puede contener letras y espacios.");
        }

        if (!preg_match("/^\d{10}$/", $telefono)) {
            die("Error: El teléfono debe contener exactamente 10 dígitos.");
        }

        // Encriptar contraseña
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

        // Insertar en la base de datos
        $stmt = $this->conexion->prepare("INSERT INTO datos (nombre, apellido, correo, telefono, contraseña) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $apellido, $correo, $telefono, $contraseña_hash);

        if ($stmt->execute()) {
            echo "<script>alert('Registro exitoso'); window.location.href='login.php';</script>";
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        $stmt->close();
    }

    public function __destruct() {
        $this->conexion->close();
    }
}

if (isset($_POST['enviar'])) {
    $registro = new RegistroUsuario();
    $registro->registrar(
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['correo'],
        $_POST['telefono'],
        $_POST['contraseña']
    );
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
            <p>Bienvenido. Por favor, ingrese sus datos para completar
el registro.</p>
            <form id="registerForm" method="post">
                <label for="username">Nombre:</label>
                <input name="nombre" type="text" id="username"
placeholder="Nombre" required
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo
letras y espacios.">

                <label for="lastname">Apellido:</label>
                <input name="apellido" type="text" id="lastname"
placeholder="Apellido" required
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo
letras y espacios.">

                <label for="email">Correo:</label>
                <input name="correo" type="email" id="email"
placeholder="Correo" required>

                <label for="number">Teléfono:</label>
                <input name="telefono" type="tel" id="number"
placeholder="Teléfono" required
                       pattern="\d{10}" maxlength="10" title="Debe
contener exactamente 10 dígitos.">

                <label for="password">Contraseña:</label>
                <input name="contraseña" type="password" id="password"
placeholder="Contraseña" required>

                <button type="submit" name="enviar">Registrarme</button>
            </form>
            <p class="register-link"><a href="login.php">Iniciar Sesión</a></p>
        </div>
        <div class="image-container">
            <img src="img/imagen.register.jpeg" alt="Perro y gato">
        </div>
    </div>

    <script type="text/javascript">
    document.getElementById('registerForm').addEventListener('submit',
function (e) {
        const user = document.getElementById('username').value.trim();
        const lastname = document.getElementById('lastname').value.trim();
        const email = document.getElementById('email').value.trim();
        const number = document.getElementById('number').value.trim();
        const pass = document.getElementById('password').value.trim();

        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
        const phoneRegex = /^\d{10}$/;

        if (user === '' || lastname == '' || email === '' || number
=== '' || pass === '') {
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

