<?php
session_start();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoIngresado = $_POST["codigo"];
    if ($codigoIngresado == $_SESSION["codigo_recuperacion"]) {
        header("Location: nuevaContraseña.php");
        exit;
    } else {
        $mensaje = "❌ Código incorrecto. Intenta de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código - PetLittle</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link rel="stylesheet" href="../assets/css/verificar.css">
</head>
<body>
<div class="card">
    <h2>Verificación de Código</h2>
    <p>Ingresa el código que recibiste por correo</p>
    <form method="POST">
        <input type="text" name="codigo" placeholder="Código de recuperación" required>
        <button type="submit">Verificar</button>
    </form>
    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>
</div>
</body>
</html>


