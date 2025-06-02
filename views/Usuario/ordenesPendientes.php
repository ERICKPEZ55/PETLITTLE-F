<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes Pendientes</title>
    <link rel="stylesheet" href="../../assets/css/ordenesPendientes.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
</head>
<body> 

    <header>
        <div class="logo">
            <img src="../../assets/img/logo negativo.png" alt="logo" class="logo-img">
        </div>

        <div class="usuario-info">
            <img src="../../assets/img/avatarusuario.png" alt="Admin" class="adminimg">
            <span class="textousuario"><?= htmlspecialchars($nombreUsuario) ?></span>
        </div>

    </header>

    <aside>
        <ul>
            <li><a href="../Usuario/agendamiento.php">← Volver</a></li>
            <li><a href="agendamientoCalendario.php">Agendar Cita</a></li>
            <li><a href="../gestionCitas/tablasCitas.php">Citas Agendadas </a></li>
            <li><a href="laboratorios.php">Laboratorio Clinico</a></li>
            <li><a href="ordenesPendientes.php">Ordenes pendientes</a></li>
        </ul>
    </aside>

    <main>
        <table>
            <thead>
                <tr>
                <th>Mascota</th>
                <th>Especialidad</th>
                <th>Fecha Orden</th>
                <th>Veterinario</th>
                <th>Estado</th>
                <th>Acciones</th>
                </tr>
            </thead>
                <tr>
                <td>Max</td>
                <td>Oftalmología</td>
                <td>2025-04-05</td>
                <td>Dr. Ramos</td>
                <td><span class="estado-pendiente">Pendiente</span></td>
                <td><button class="btn-ver">Ver Orden</button></td>
                </tr>
        </table>
    </main>

</body>
</html>
