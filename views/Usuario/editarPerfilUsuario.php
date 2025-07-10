<?php
  session_start();
  require_once(__DIR__ . '/../../configuracion/conexion.php');

  $pdo = conexion();

  // Validar si el usuario está autenticado
  if (!isset($_SESSION['id_usuario'])) {
      die("Error: No has iniciado sesión.");
  }

  $id_usuario = $_SESSION['id_usuario'];
  $stmt = $pdo->prepare("SELECT nombre, apellido, correo, telefono FROM usuarios WHERE id_usuario = ?");
  $stmt->execute([$id_usuario]);
  $usuario = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/editarPerfilUsuario.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Editar Perfil</title>

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
        <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>
    
    <div class="flex-container">
      <!-- Sidebar -->
      <div class="sidebar">
            <a href="agendamientoCalen.php">Agendar Cita</a>
            <a href="../gestionCitas/tablasCitas.php">Citas Agendadas</a>
            <a href="laboratorios.php">Laboratorio Clínico</a>
      </div>

      <!-- Formulario -->
      <div class="form-container">
        <h2>Editar Perfil Cliente</h2>
        <form id="formPerfil" class="form-grid">
          <input type="text" id="nombres" placeholder="Nombre(s)" value="<?= htmlspecialchars($usuario['nombre']) ?>" />
          <input type="text" id="apellidos" placeholder="Apellido(s)" value="<?= htmlspecialchars($usuario['apellido']) ?>" />
          <input type="email" id="correo" placeholder="Correo electrónico" value="<?= htmlspecialchars($usuario['correo']) ?>" />
          <input type="tel" id="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($usuario['telefono']) ?>" />

          <div class="form-buttons">
            <button type="button" id="btnGuardar" class="btn-save">Guardar Cambios</button>
            <button type="button" id="btnEliminar" class="btn-delete">Eliminar Cuenta</button>

         </div>
        </form>
      </div>
    </div>

<script src="../../assets/js/editarPerfilUsuarios.js"></script>

  <!-- SweetAlert2 confirmaciones -->
  <!--<script>
    document.getElementById('btnGuardar').addEventListener('click', function () {
      Swal.fire({
        icon: 'success',
        title: 'Perfil actualizado',
        text: 'Los cambios han sido guardados exitosamente.',
        confirmButtonColor: '#3085d6'
      });
    });
  </script>-->

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
