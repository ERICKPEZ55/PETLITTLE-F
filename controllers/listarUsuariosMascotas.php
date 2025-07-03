<?php
require_once(__DIR__ . '/../configuracion/conexion.php');

try {
    $pdo = conexion();

    $sql = "SELECT u.id_usuario,
                   CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo,
                   u.correo,
                   u.telefono,
                   COUNT(m.id_mascota) AS cantidad_mascotas,
                   GROUP_CONCAT(m.nombre SEPARATOR ', ') AS nombres_mascotas
            FROM usuarios u
            LEFT JOIN mascotas m ON u.id_usuario = m.id_usuario
            GROUP BY u.id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($usuarios);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
