<?php
require_once("../../configuracion/conexion.php");
$conexion = conexion();

if (isset($_GET['id_usuario'])) {
    $idUsuario = $_GET['id_usuario'];

    $stmt = $conexion->prepare("SELECT id_mascota, nombre FROM mascotas WHERE id_usuario = ?");
    $stmt->execute([$idUsuario]);
    $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($mascotas);
}
?>
