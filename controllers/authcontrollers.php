<?php
session_start();

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

        if ($usuario->registrar($data)) {
            $_SESSION['registro_exitoso'] = true;
            header('Location: ../models/login.php');
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
            // Definimos aquí las listas de correos para cada rol
            $admins = ['petlittle.soporte@gmail.com'];
            $empleados = ['mpautorresb.06@gmail.com'];
            $clientes = ['santiromerito30@gmail.com'];

            // Asignar rol según el correo
            if (in_array($correo, $admins)) {
                $rol = 'administrador';
                $redirect = '../views/admin/perfil-admin.html';
            } elseif (in_array($correo, $empleados)) {
                $rol = 'empleado';
                $redirect = '../views/Empleado/empleado.html';
            } elseif (in_array($correo, $clientes)) {
                $rol = 'cliente';
                $redirect = '../views/Usuario/agendamiento.php';
            } else {
                // Si no está en ninguna lista, negar acceso
                $_SESSION['error_login'] = "No tienes permisos para acceder.";
                header('Location: ../models/login.php');
                exit;
            }

            // Guardar info en sesión, incluyendo rol asignado
            $_SESSION['usuario'] = $user;
            $_SESSION['usuario']['rol'] = $rol;

            header("Location: $redirect");
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
