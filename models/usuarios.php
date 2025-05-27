<?php
require_once __DIR__ . '../configuracion/conexion.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrar($data) {
        $sql = "INSERT INTO datos (nombre, apellido, correo, telefono, contraseña)
                VALUES (:nombre, :apellido, :correo, :telefono, :contraseña)";
        $stmt = $this->pdo->prepare($sql);
        $data['contraseña'] = password_hash($data['contraseña'], PASSWORD_DEFAULT);
        return $stmt->execute($data);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE correo = :correo");
        $stmt->execute(['correo' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['contraseña'])) {
            return $user;
        }
        return false;
    }
}