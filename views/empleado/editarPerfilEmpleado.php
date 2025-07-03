<?php
  session_start();
  require_once(__DIR__ . '/../../configuracion/conexion.php');

  $pdo = conexion();

  // Validar si el usuario está autenticado
  if (!isset($_SESSION['id_empleado'])) {
      die("Error: No has iniciado sesión.");
  }

  $id_empleado = $_SESSION['id_empleado'];
  $stmt = $pdo->prepare("SELECT nombre, apellido, usuario, telefono FROM empleados WHERE id_empleado = ?");
  $stmt->execute([$id_empleado]);
  $empleado = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/editarPerfilEmpleados.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Editar Perfil Empleado</title>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header class="header">
        <div class="header-left">
            <div class="logo-container">
                <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
            </div>
        </div>
        <div class="titulo-header">
            <h1>Editar Perfil</h1>
        </div>
        <a href="../empleado/empleado.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <div class="flex-container">
      <!-- Sidebar -->
      <div class="sidebar">
            <a href="../agendaCitasEmpleado/gestionAgendaCitas.html">Gestión de Agenda y Citas</a>
            <a href="../ordenesLab/ordenes.html">Ordenes de Laboratorio</a>
            <a href="../gestionMascotas/gestion.html">Gestión de Mascotas</a>
            <a href="../gestionMascotas/reportes.html">Reportes Medicos</a>
      </div>

      <!-- Formulario -->
      <div class="form-container">
        <h2>Editar Perfil Cliente</h2>
        <form id="formPerfil" class="form-grid">
          <input type="text" id="nombres" name="nombre" placeholder="Nombre(s)" value="<?= htmlspecialchars($empleado['nombre']) ?>" />
          <input type="text" id="apellidos" name="apellido" placeholder="Apellido(s)" value="<?= htmlspecialchars($empleado['apellido']) ?>" />
          <input type="email" id="usuario" name="usuario" placeholder="Correo electrónico" value="<?= htmlspecialchars($empleado['usuario']) ?>" />
          <input type="tel" id="telefono" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($empleado['telefono']) ?>" />

          <div class="form-buttons">
            <button type="button" id="btnGuardar" class="btn-save">Guardar Cambios</button>
            <button type="button" id="btnEliminar" class="btn-delete">Eliminar Cuenta</button>
          </div>
        </form>

      </div>
    </div>

  <script src="../../assets/js/editarPerfilEmpleados.js"></script>
</body>
</html>
