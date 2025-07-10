<?php
session_start();

// Verifica si hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../models/login.php");
    exit();
}
