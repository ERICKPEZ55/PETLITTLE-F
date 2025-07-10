<?php
$conexion = new mysqli("localhost", "root", "", "prueba");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT 
            m.id_mascota,
            m.nombre AS nombre_mascota,
            m.raza,
            m.sexo,
            CONCAT(c.nombre, ' ', c.apellido) AS nombre_dueño,
            CONCAT('/PetLittle/assets/img/mascotas/', m.imagen) AS imagen
        FROM mascotas m
        JOIN usuarios c ON m.id_usuario = c.id_usuario
        ORDER BY m.id_mascota DESC";

$resultado = $conexion->query($sql);

$mascotas = [];

while ($fila = $resultado->fetch_assoc()) {
    $mascotas[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($mascotas);

$conexion->close();
?>