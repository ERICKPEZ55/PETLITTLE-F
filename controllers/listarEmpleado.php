<?php
$conexion = new mysqli("localhost", "root", "", "prueba");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT * FROM empleados ORDER BY id_empleado DESC");

$empleados = [];

while ($fila = $resultado->fetch_assoc()) {
    $empleados[] = $fila;
}

echo json_encode($empleados);
$conexion->close();
?>
