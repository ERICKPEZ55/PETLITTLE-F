<?php
session_start();

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "prueba");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtener datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM datos WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Comparar contraseñas directamente (sin hash)
        if ($contraseña === $usuario['contraseña']) {
            $_SESSION['usuario'] = $usuario['correo'];
    
            // Redirección según el tipo de usuario (correo)
            if ($usuario['correo'] === 'juan.24@gmail.com') { //usuario
                header("Location: agendamiento.html");
            } elseif ($usuario['correo'] === 'andres@gmail.com') {//empleado
                header("Location: empleado.html");
            } elseif ($usuario['correo'] === "sofia@gmail.com"){//admin
                header("Location: vista.html");
            } else { // Cualquier otro correo no especificado
                header("Location: agendamiento.html");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no registrado.";
    }
    
    $stmt->close();
    $conexion->close();
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
    window.onload = function () {
        // Si existe sesión anterior, autocompleta
        if (sessionStorage.getItem("correo")) {
            document.getElementById("email").value = sessionStorage.getItem("correo");
        }
        if (sessionStorage.getItem("contraseña")) {
            document.getElementById("password").value = sessionStorage.getItem("contraseña");
        }
    };

    document.getElementById("loginForm").onsubmit = function () {
        // Guardar temporalmente en sessionStorage (se borra al cerrar o recargar)
        sessionStorage.setItem("correo", document.getElementById("email").value);
        sessionStorage.setItem("contraseña", document.getElementById("password").value);
    };

    // Opcional: limpiar los campos si recargas
    window.addEventListener("beforeunload", function () {
        sessionStorage.removeItem("correo");
        sessionStorage.removeItem("contraseña");
    });
</script>

</body>
</html>
