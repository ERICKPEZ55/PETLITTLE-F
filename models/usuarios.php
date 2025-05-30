<?php
require_once __DIR__ . '/../configuracion/conexion.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrar($data) {
        $sql = "INSERT INTO datos (nombre, apellido, correo, telefono, contrasena)
                VALUES (:nombre, :apellido, :correo, :telefono, :contrasena)";
        $stmt = $this->pdo->prepare($sql);

        // Verificar que todos los datos estén definidos
        if (!isset($data['nombre'], $data['apellido'], $data['correo'], $data['telefono'], $data['contrasena'])) {
            throw new Exception("Faltan datos para registrar el usuario.");
        }

        // Hashear la contraseña
        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        // Ejecutar la consulta con los parámetros correctos
        return $stmt->execute([
            'nombre'     => $data['nombre'],
            'apellido'   => $data['apellido'],
            'correo'     => $data['correo'],
            'telefono'   => $data['telefono'],
            'contrasena' => $data['contrasena'],
        ]);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE correo = :correo");
        $stmt->execute(['correo' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contrasena'])) {
            return $user;
        }
        return false;
    }
}
