<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = new mysqli("localhost", "root", "", "prueba");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM datos WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

        // Insertar datos con la contraseña encriptada
        $insertar = "INSERT INTO datos (correo, contraseña) 
                    VALUES ('$correo', '$contraseña_hash')";

        if (password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario'] = $usuario['correo'];

            // Redirección por tipo de usuario
            if ($usuario['correo'] === 'juan.24@gmail.com') {
                header("Location: agendamiento.html");
            } elseif ($usuario['correo'] === 'andres@gmail.com') {
                header("Location: empleado.html");
            } elseif ($usuario['correo'] === "sofia@gmail.com") {
                header("Location: vista.html");
            } else {
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

    window.addEventListener("beforeunload", function () {
        sessionStorage.removeItem("correo");
        sessionStorage.removeItem("contraseña");
    });
</script>

</body>
</html>
