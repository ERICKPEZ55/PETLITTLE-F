<?php
session_start();

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'empleado'])) {
    header("Location: ../views/acceso_denegado.php");
    exit();
}

