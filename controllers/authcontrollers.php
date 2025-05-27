<?php
require_once 'usuarios.php';
require_once __DIR__ . '../BaseDatos/conexion.php';
session_start();

$usuario = new Usuario($pdo);

// Registro
if (isset($_POST['registrar'])) {
    $data = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'correo' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'contraseña' => $_POST['contraseña'],
    ];
    $usuario->registrar($data);
    header('Location: ../login/login.php');

    exit;
}

// Login
if (isset($_POST['login'])) {
    $user = $usuario->login($_POST['correo'], $_POST['contraseña']);
    if ($user) {
        $_SESSION['usuario'] = $user;
        header('Location: ../Usuario/agendamiento.php');
    } else {
        echo "Credenciales incorrectas.";
    }
}