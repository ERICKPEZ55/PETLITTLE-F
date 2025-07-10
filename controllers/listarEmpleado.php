<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "prueba");
if ($conexion->connect_error) {
    echo json_encode(["success" => false, "error" => "Conexión fallida: " . $conexion->connect_error]);
    exit;
}

$sql = "SELECT 
            e.id_empleado, 
            e.nombre, 
            e.apellido, 
            e.rol, 
            es.nombre AS especialidad, 
            e.usuario, 
            e.telefono, 
            e.contrasena
        FROM empleados e
        LEFT JOIN especialidades es ON e.id_especialidad = es.id_especialidad
        ORDER BY e.id_empleado DESC";

$resultado = $conexion->query($sql);

$empleados = [];

while ($fila = $resultado->fetch_assoc()) {
    $empleados[] = $fila;
}

echo json_encode($empleados, JSON_UNESCAPED_UNICODE);
$conexion->close();
