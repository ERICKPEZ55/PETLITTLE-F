<?php
session_start();

require_once(__DIR__ . '/../models/usuarios.php');
require_once(__DIR__ . '/../configuracion/conexion.php');
require_once(__DIR__ . '/correoRegistro.php');

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
        'rol'        => 'cliente' // Se asigna el rol automáticamente
    ];

    try {
        // Validar si el correo ya existe
        if ($usuario->existeCorreo($data['correo'])) {
            $_SESSION['error_registro'] = "El correo ya está registrado.";
            header('Location: ../models/registro.php');
            exit;
        }

        // Validar si el teléfono ya existe
        if ($usuario->existeTelefono($data['telefono'])) {
            $_SESSION['error_registro'] = "El número de teléfono ya está registrado.";
            header('Location: ../models/registro.php');
            exit;
        }

        // Registrar usuario
        if ($usuario->registrar($data)) {
            // Enviar correo de confirmación
            enviarCorreoRegistro($data['correo'], $data['nombre']);

            // Guardar estado de éxito en sesión
            $_SESSION['registro_exitoso'] = true;

            // Redirigir a registro.php para mostrar el mensaje con SweetAlert
            header('Location: ../models/registro.php');
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
            // Listas de correos especiales
            $admins = ['derlyvillalobos0702z@gmail.com'];
            $empleados = ['mpautorresb.06@gmail.com'];

            // Asignar rol y redirección
            if (in_array($correo, $admins)) {
                $rol = 'administrador';
                $redirect = '../views/admin/perfilAdmin.php';
            } elseif (in_array($correo, $empleados)) {
                $rol = 'empleado';
                $redirect = '../views/empleado/empleado.php';
            } else {
                // Cualquier otro correo es cliente
                $rol = 'cliente';
                $redirect = '../views/usuario/agendamiento.php';
            }

            // Guardar en sesión y redirigir
            $_SESSION['usuario'] = $user;
            $_SESSION['usuario']['rol'] = $rol;

            $_SESSION['id_usuario'] = $user['id_usuario'];

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
