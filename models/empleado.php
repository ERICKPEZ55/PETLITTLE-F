<?php
require_once __DIR__ . '/../configuracion/conexion.php';

class Empleado {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM datos WHERE rol = 'empleado'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar($data) {
        $sql = "INSERT INTO datos (nombre, apellido, correo, telefono, contrasena, rol)
                VALUES (:nombre, :apellido, :correo, :telefono, :contrasena, 'empleado')";

        $stmt = $this->pdo->prepare($sql);
        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        return $stmt->execute([
            'nombre'     => $data['nombre'],
            'apellido'   => $data['apellido'],
            'correo'     => $data['correo'],
            'telefono'   => $data['telefono'],
            'contrasena' => $data['contrasena'],
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM datos WHERE id_usuario = :id AND rol = 'empleado'");
        return $stmt->execute(['id' => $id]);
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE id_usuario = :id AND rol = 'empleado'");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $data) {
        $sql = "UPDATE datos SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono
                WHERE id_usuario = :id AND rol = 'empleado'";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'        => $id,
            'nombre'    => $data['nombre'],
            'apellido'  => $data['apellido'],
            'correo'    => $data['correo'],
            'telefono'  => $data['telefono'],
        ]);
    }
}
