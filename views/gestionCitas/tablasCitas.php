<?php
session_start();
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Citas - PetLittle</title>
  <link rel="stylesheet" href="../../assets/css/tablaCitas.css" />
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      <li><a href="tablasCitas.php">Citas Agendadas</a></li>
      <li><a href="../usuario/laboratorios.php">Laboratorio Clínico</a></li>
      <li><a href="../usuario/ordenesPendientes.php">Órdenes pendientes</a></li>
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
          <th>Cancelar</th>
        </tr>
      </thead>
      <tbody id="tablaCitas">
        <!-- Citas cargadas por controlCitas.js -->
      </tbody>
    </table>
  </main>

  <!-- Modal para editar cita -->
  <div id="modalAgregar" class="modal">
    <div class="modal-content">
      <h2 id="tituloModal">Editar Cita</h2>
      <input type="text" id="nombreCliente" placeholder="Nombre del cliente" disabled>
      <input type="email" id="correoCliente" placeholder="Correo" disabled>
      <input type="text" id="telefonoCliente" placeholder="Teléfono" disabled>
      <input type="text" id="nombreMascota" placeholder="Nombre de la mascota" disabled>

      <label for="tipoCita">Tipo de cita</label>
      <select id="tipoCita"></select>
      <label for="fechaHora">Fecha y hora</label>
      <input type="datetime-local" id="fechaHora">

      <button id="guardarCita">Guardar</button>
      <button id="cerrarAgregar">Cancelar</button>
    </div>
  </div>

  <script src="../../assets/js/controlCitas.js"></script>
</body>
</html>
