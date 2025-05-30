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
        if (!isset($data['nombre'], $data['apellido'], $data['correo'], $data['telefono'], $data['contrasena'])) {
            throw new Exception("Faltan datos para registrar el usuario.");
        }

        if ($this->existeCorreo($data['correo'])) {
            throw new Exception("El correo ya está registrado.");
        }

        if ($this->existeTelefono($data['telefono'])) {
            throw new Exception("El número de teléfono ya está registrado.");
        }

        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO datos (nombre, apellido, correo, telefono, contrasena)
                VALUES (:nombre, :apellido, :correo, :telefono, :contrasena)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nombre'     => $data['nombre'],
            'apellido'   => $data['apellido'],
            'correo'     => $data['correo'],
            'telefono'   => $data['telefono'],
            'contrasena' => $data['contrasena'],
        ]);
    }

    /**
     * Iniciar sesión
     */
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE correo = :correo");
        $stmt->execute(['correo' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contrasena'])) {
            return $user;
        }

        return false;
    }

    /**
     * Verifica si un correo ya está registrado
     */
    public function existeCorreo($correo) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM datos WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch() !== false;
    }

    /**
     * Verifica si un teléfono ya está registrado
     */
    public function existeTelefono($telefono) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM datos WHERE telefono = ?");
        $stmt->execute([$telefono]);
        return $stmt->fetch() !== false;
    }
}
