<?php
include 'conexion.php';

$result = $conn->query("SELECT * FROM usuarios");

while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td class='td-nombre'>{$row['nombre']}</td>
        <td class='td-apellido'>{$row['apellidos']}</td>
        <td class='td-rol'>{$row['rol']}</td>
        <td>{$row['usuario']}</td>
        <td class='td-telefono'>{$row['telefono']}</td>
        <td>{$row['contrasena']}</td>
        <td>
            <button class='editarBtn' data-id='{$row['id']}'>✏️</button>
            <button class='eliminarBtn' data-id='{$row['id']}'>🗑️</button>
        </td>
    </tr>";
}

$conn->close();
?>