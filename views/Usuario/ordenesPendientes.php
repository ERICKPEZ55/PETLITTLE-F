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
        <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <aside>
        <ul>
            <li><a href="agendamientoCalen.php">Agendar Cita</a></li>
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
                <th>Acciones</th>
                </tr>
            </thead>
                <tr>
                <td>Max</td>
                <td>Oftalmología</td>
                <td>2025-04-05</td>
                <td>Dr. Ramos</td>
                <td><button class="btn-ver">Ver Orden</button></td>
                </tr>
        </table>
    </main>
    <!-- Overlay y Modal para mostrar detalle -->
    <div id="overlay" class="overlay" onclick="cerrarDetalle()"></div>
    <div id="detalleModal" class="ver-detalle">
        <h2>Orden laboratorio</h2>
        <div id="detalleContenido">
            <!-- Aquí se inserta dinámicamente el contenido de la orden -->
        </div>
        <button class="btn-cerrar" onclick="cerrarDetalle()">Cerrar</button>
    </div>


    <script>
        const botonesVer = document.querySelectorAll('.btn-ver');
        const overlay = document.getElementById('overlay');
        const modal = document.getElementById('detalleModal');
        const contenido = document.getElementById('detalleContenido');

        botonesVer.forEach(boton => {
            boton.addEventListener('click', () => {
                // Aquí puedes cargar dinámicamente los datos reales
                contenido.innerHTML = `
                    <p><strong>Mascota:</strong> Max</p>
                    <p><strong>Especialidad:</strong> Oftalmología</p>
                    <p><strong>Fecha:</strong> 2025-04-05</p>
                    <p><strong>Veterinario:</strong> Dr. Ramos</p>
                    <p><strong>Notas:</strong> Revisar córnea izquierda.</p>
                `;
                overlay.style.display = 'block';
                modal.style.display = 'block';
            });
        });

        function cerrarDetalle() {
            overlay.style.display = 'none';
            modal.style.display = 'none';
        }
    </script>

    <!-- ✅ Script para cerrar sesión tras inactividad -->
  <script>
    let timeoutInactivity;

    function cerrarSesionPorInactividad() {
        window.location.href = '../../models/logout.php';
    }

    function reiniciarTemporizador() {
        clearTimeout(timeoutInactivity);
        timeoutInactivity = setTimeout(cerrarSesionPorInactividad, 300000); // 5 minutos
    }

    window.onload = reiniciarTemporizador;
    document.onmousemove = reiniciarTemporizador;
    document.onkeydown = reiniciarTemporizador;
    document.onclick = reiniciarTemporizador;
  </script>

</body>
</html>
