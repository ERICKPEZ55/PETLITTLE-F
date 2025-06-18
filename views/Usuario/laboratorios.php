<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios Clinicos</title>
    <link rel="stylesheet" href="../../assets/css/laboratorios.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
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
            <li><a href="laboratorios.php">Laboratorio Clinico</a></li>
            <li><a href="ordenesPendientes.php">Ordenes pendientes</a></li>
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
                <th>Acciones</th>
                </tr>
            </thead>
            <tr>
                <td>2025-04-05</td>
                <td>Max</td>
                <td>Ana Lopez</td>
                <td>Hemograma</td>
                <td>Laboratorio A</td>
                <td><span class="estado-pendiente">Pendiente</span></td>
                <td><button class="btn-ver">Descargar Orden</button></td>
            </tr>
        </table>
    </main>
</body>
</html>