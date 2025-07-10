<?php
// Evitar cualquier salida antes del JSON
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Intentar decodificar los datos JSON del body
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Datos JSON no válidos"]);
    exit;
}

// Conexión a base de datos
$conexion = new mysqli("localhost", "root", "", "prueba");
if ($conexion->connect_error) {
    echo json_encode(["success" => false, "error" => "Error de conexión"]);
    exit;
}

// Sanitización
$nombre = $conexion->real_escape_string($data['nombre']);
$apellido = $conexion->real_escape_string($data['apellido']);
$rol = $conexion->real_escape_string($data['rol']);
$especialidad = $conexion->real_escape_string($data['especialidad']);
$usuario = $conexion->real_escape_string($data['usuario']);
$telefono = $conexion->real_escape_string($data['telefono']);
$contrasena = $conexion->real_escape_string($data['contrasena']);

// Inserción
$sql = "INSERT INTO empleados (nombre, apellido, rol, id_especialidad, usuario, telefono, contrasena)
        VALUES ('$nombre', '$apellido', '$rol', $especialidad, '$usuario', '$telefono', '$contrasena')";

if ($conexion->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conexion->error]);
}

$conexion->close();
ob_end_flush();
