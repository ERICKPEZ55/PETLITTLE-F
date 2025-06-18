<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - PetLittle</title>
    <link rel="stylesheet" href="../../assets/css/tablasCitas.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
</head>
<body> 

    <header>
        <div class="logo">
            <img src="../../assets/img/Logo negativo.png" alt="PetLittle" class="logonav">
        </div>
        <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <aside>
        <ul>
            <li><a href="../usuario/agendamientoCalendario.php">Agendar Cita</a></li>
            <li><a href="tablasCitas.php">Citas Agendadas </a></li>
            <li><a href="../usuario/laboratorios.php">Laboratorio Clinico</a></li>
            <li><a href="../usuario/ordenesPendientes.php">Ordenes pendientes</a></li>
        </ul>
    </aside>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Nombre Mascota</th>
                    <th>Cita</th>
                    <th>Fecha y hora</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="tablaCitas">
                <!-- Aquí van las filas de citas -->
            </tbody>
        </table>

        <button id="btnAgregar">+ boton de ejemplo</button>
    </main>

    <div id="modalAgregar" class="modal">
        <div class="modal-content">
            <h2 id="tituloModal">Agendar Cita</h2>
            <input type="text" id="nombreCliente" placeholder="Nombre del cliente">
            <input type="email" id="correoCliente" placeholder="Correo">
            <input type="text" id="telefonoCliente" placeholder="Teléfono">
            <input type="text" id="nombreMascota" placeholder="Nombre de la mascota">
            <select id="tipoCita">
                <option value="Vacunación">Vacunación</option>
                <option value="Desparasitación">Desparasitación</option>
                <option value="Consulta general">Consulta general</option>
                <option value="Cirugía menor">Cirugía menor</option>
                <option value="Control anual">Control anual</option>
                <option value="Consulta dental">Consulta dental</option>
            </select>
            <input type="datetime-local" id="fechaHora">
            <button id="guardarCita">Guardar</button>
            <button id="cerrarAgregar">Cancelar</button>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>

</body>
</html>
