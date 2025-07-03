<?php
include_once('../../configuracion/conexion.php');

// Consulta para obtener las órdenes con JOIN para mostrar paciente y propietario
$sql = "SELECT o.fecha, m.nombre AS paciente, m.tipo, u.nombre AS propietario, o.pruebas, o.laboratorio 
        FROM ordenes_laboratorio o
        JOIN mascotas m ON o.id_mascota = m.id_mascota
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        ORDER BY o.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ordenes as $orden) {
    echo "<tr>
            <td>{$orden['fecha']}</td>
            <td>{$orden['paciente']} ({$orden['tipo']})</td>
            <td>{$orden['propietario']}</td>
            <td>{$orden['pruebas']}</td>
            <td>{$orden['laboratorio']}</td>
            <td>
                <button class='btn-action view-details'><i class='fas fa-eye'></i> Ver</button>
                <button class='btn-action reorder'><i class='fas fa-sync-alt'></i> Reordenar</button>
            </td>
        </tr>";
}
?>
