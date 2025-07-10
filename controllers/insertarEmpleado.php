<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rol = $_POST['rol'];
$usuario = $_POST['usuario'];
$telefono = $_POST['telefono'];
$contrasena = $_POST['contrasena'];

$sql = "INSERT INTO empleados (nombre, apellido, rol, usuario, telefono, contrasena) 
        VALUES ('$nombre', '$apellido', '$rol', '$usuario', '$telefono', '$contrasena')";

if ($conn->query($sql) === TRUE) {
    echo "Empleado agregado";
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
