<?php
require_once __DIR__ . '/../configuracion/conexion.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($data) {
        if (!isset($data['nombre'], $data['apellido'], $data['correo'], $data['telefono'], $data['contrasena'], $data['rol'])) {
            throw new Exception("Faltan datos para registrar el usuario.");
        }

        if ($this->existeCorreo($data['correo'])) {
            throw new Exception("El correo ya está registrado.");
        }

        if ($this->existeTelefono($data['telefono'])) {
            throw new Exception("El número de teléfono ya está registrado.");
        }

        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellido, correo, telefono, contrasena, rol)
                VALUES (:nombre, :apellido, :correo, :telefono, :contrasena, :rol)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nombre'     => $data['nombre'],
            'apellido'   => $data['apellido'],
            'correo'     => $data['correo'],
            'telefono'   => $data['telefono'],
            'contrasena' => $data['contrasena'],
            'rol'        => $data['rol']
        ]);
    }

    /**
     * Iniciar sesión
     */
    public function login($email, $password) {
    // Buscar primero en la tabla datos (clientes/administradores)
    $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['contrasena'])) {
        return $user;
    }

    // Si no está en datos, buscar en empleados
    $stmt = $this->pdo->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
    $stmt->execute(['usuario' => $email]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empleado && $password === $empleado['contrasena']) {
        // Adaptamos los datos para que tengan el mismo formato que 'datos'
        return [
            'id_usuario' => $empleado['id_empleado'],
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'correo' => $empleado['usuario'],
            'telefono' => $empleado['telefono'] ?? '',
            'rol' => $empleado['rol'],
        ];
    }

    return false;
}

    /**
     * Verifica si un correo ya está registrado
     */
    public function existeCorreo($correo) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch() !== false;
    }

    /**
     * Verifica si un teléfono ya está registrado
     */
    public function existeTelefono($telefono) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE telefono = ?");
        $stmt->execute([$telefono]);
        return $stmt->fetch() !== false;
    }
}
