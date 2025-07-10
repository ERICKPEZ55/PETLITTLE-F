<?php
require_once("../../configuracion/conexion.php");
$conexion = conexion();

if (isset($_GET['id_mascota'])) {
    $idMascota = $_GET['id_mascota'];

    $stmt = $conexion->prepare("
        SELECT m.nombre, m.raza, m.tipo, u.nombre AS propietario
        FROM mascotas m
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE m.id_mascota = ?
        LIMIT 1
    ");
    $stmt->execute([$idMascota]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($resultado);
}
?>
