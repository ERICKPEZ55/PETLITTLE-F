<?php
session_start();
require_once('../../configuracion/conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../models/login.php");
    exit;
}

$pdo = conexion();
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT o.fecha_creacion, o.pruebas, o.laboratorio_destino,
               m.nombre AS nombre_mascota,
               u.nombre AS nombre_usuario, u.apellido
        FROM ordenes_laboratorio o
        JOIN mascotas m ON o.id_mascota = m.id_mascota
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE u.id_usuario = :id_usuario
        ORDER BY o.fecha_creacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios Clínicos</title>
    <link rel="stylesheet" href="../../assets/css/laboratorios.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../assets/img/logo negativo.png" alt="logo" class="logo-img">
        </div>
        <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <aside>
        <ul>
            <li><a href="agendamientoCalendario.php">Agendar Cita</a></li>
            <li><a href="../gestionCitas/tablasCitas.php">Citas Agendadas</a></li>
            <li><a href="laboratorios.php">Laboratorio Clínico</a></li>
            <li><a href="ordenesPendientes.php">Órdenes pendientes</a></li>
        </ul>
    </aside>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Propietario</th>
                    <th>Pruebas</th>
                    <th>Laboratorio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?= htmlspecialchars($orden['fecha_creacion']) ?></td>
                    <td><?= htmlspecialchars($orden['nombre_mascota']) ?></td>
                    <td><?= htmlspecialchars($orden['nombre_usuario'] . ' ' . $orden['apellido']) ?></td>
                    <td><?= htmlspecialchars($orden['pruebas']) ?></td>
                    <td><?= htmlspecialchars($orden['laboratorio_destino']) ?></td>
                    <td><span class="estado-pendiente">Pendiente</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
