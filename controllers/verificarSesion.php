<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al login si no ha iniciado sesión
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['admin'] !== 'admin') {
    header("Location: accesoDenegado.php");
    exit();
}

if ($_SESSION['rol'] !== 'empleado') {
    header("Location: accesoDenegado.php");
    exit();
}

?>
