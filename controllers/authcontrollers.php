<?php
session_start();  // Iniciar sesión al principio del script

require_once(__DIR__ . '/../models/usuarios.php');
require_once(__DIR__ . '/../configuracion/conexion.php');

$pdo = conexion();
$usuario = new Usuario($pdo);

// Registro
if (isset($_POST['registrar'])) {
    $data = [
        'nombre'     => $_POST['nombre'] ?? '',
        'apellido'   => $_POST['apellido'] ?? '',
        'correo'     => $_POST['correo'] ?? '',
        'telefono'   => $_POST['telefono'] ?? '',
        'contrasena' => $_POST['contrasena'] ?? '',
    ];

    try {
        if ($usuario->registrar($data)) {
            $_SESSION['registro_exitoso'] = true;
            header('Location: ../models/login.php'); // Ruta al login donde está el alert
            exit;
        } else {
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header('Location: ../views/register.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error_registro'] = "Error: " . $e->getMessage();
        header('Location: ../views/register.php');
        exit;
    }
}

// Login
if (isset($_POST['login'])) {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    
    $user = $usuario->login($correo, $contrasena);

    if ($user) {
        $_SESSION['usuario'] = $user;
        header('Location: ../views/Usuario/agendamiento.php');
        exit;
    } else {
        $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
        header('Location: ../models/login.php');
        exit;
    }
}
