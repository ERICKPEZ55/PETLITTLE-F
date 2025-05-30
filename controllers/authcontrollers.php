<?php
session_start();  // Iniciar sesión al principio del script

require_once(__DIR__ . '/../models/usuarios.php');
require_once(__DIR__ . '/../configuracion/conexion.php');

$pdo = conexion();
$usuario = new Usuario($pdo);

// Registro
if (isset($_POST['registrar'])) {
    $data = [
        'nombre'     => trim($_POST['nombre'] ?? ''),
        'apellido'   => trim($_POST['apellido'] ?? ''),
        'correo'     => trim($_POST['correo'] ?? ''),
        'telefono'   => trim($_POST['telefono'] ?? ''),
        'contrasena' => $_POST['contrasena'] ?? '',
    ];

    try {
        // Verificar si el correo o teléfono ya existen
        if ($usuario->existeCorreo($data['correo'])) {
            $_SESSION['error_registro'] = "El correo ya está registrado.";
            header('Location: ../models/registro.php');
            exit;
        }

        if ($usuario->existeTelefono($data['telefono'])) {
            $_SESSION['error_registro'] = "El número de teléfono ya está registrado.";
            header('Location: ../models/registro.php');
            exit;
        }

        // Intentar registrar
        if ($usuario->registrar($data)) {
            $_SESSION['registro_exitoso'] = true;
            header('Location: ../models/login.php'); // Ruta al login con alert JS
            exit;
        } else {
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header('Location: ../models/registro.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error_registro'] = "Error: " . $e->getMessage();
        header('Location: ../models/registro.php');
        exit;
    }
}

// Login
if (isset($_POST['login'])) {
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    try {
        $user = $usuario->login($correo, $contrasena);

        if ($user) {
            $_SESSION['usuario'] = $user;

            // Redirigir según tipo de usuario
            switch ($user['rol']) {
                case 'administrador':
                    header('Location: ../views/Admin/index.php');
                    break;
                case 'empleado':
                    header('Location: ../views/Empleado/inicio.php');
                    break;
                default:
                    header('Location: ../views/Usuario/agendamiento.php');
                    break;
            }
            exit;
        } else {
            $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
            header('Location: ../models/login.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error_login'] = "Error: " . $e->getMessage();
        header('Location: ../models/login.php');
        exit;
    }
}
