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
        'rol'        => 'cliente'
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
            enviarCorreoRegistro($data['correo'], $data['nombre']);
            $_SESSION['registro_exitoso'] = true;
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
        // 1. Buscar en la tabla admin
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE correo = ? AND contrasena = ?");
        $stmt->execute([$correo, $contrasena]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $_SESSION['usuario'] = $admin;
            $_SESSION['usuario']['rol'] = 'administrador';
            $_SESSION['id_admin'] = $admin['id_admin'];
            header("Location: ../views/admin/perfilAdmin.php");
            exit;
        }

        // 2. Buscar en la tabla empleados
        $stmt = $pdo->prepare("SELECT * FROM empleados WHERE usuario = ? AND contrasena = ?");
        $stmt->execute([$correo, $contrasena]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empleado) {
            $_SESSION['usuario'] = $empleado;
            $_SESSION['usuario']['rol'] = 'empleado';
            $_SESSION['id_empleado'] = $empleado['id_empleado'];
            header("Location: ../views/empleado/empleado.php");
            exit;
        }

        // 3. Buscar en la tabla usuarios
        $user = $usuario->login($correo, $contrasena);
        if ($user) {
            $_SESSION['usuario'] = $user;
            $_SESSION['usuario']['rol'] = 'cliente';
            $_SESSION['id_usuario'] = $user['id_usuario'];
            header("Location: ../views/usuario/agendamiento.php");
            exit;
        }

        // Si no encontró en ninguna tabla
        $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
        header('Location: ../models/login.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['error_login'] = "Error: " . $e->getMessage();
        header('Location: ../models/login.php');
        exit;
    }
}
