<?php
  session_start();
  require_once(__DIR__ . '/../../configuracion/conexion.php');


  $pdo = conexion();

  // Validar si el usuario está autenticado
  if (!isset($_SESSION['id_admin'])) {
      die("Error: No has iniciado sesión.");
  }

  $id_admin = $_SESSION['id_admin'];
  $stmt = $pdo->prepare("SELECT nombre, apellido, correo, telefono FROM admins WHERE id_admin = ?");
  $stmt->execute([$id_admin]);
  $admin = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/editarPerfilAdmins.css">
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
        <a href="../admin/perfilAdmin.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <div class="flex-container">
      <!-- Sidebar -->
      <div class="sidebar">
        <a href="../../views/admin/usuarios.html">Gestión de clientes</a>
        <a href="../../views/admin/graficos.html">Graficas</a>
        <a href="../../views/admin/vista.html">Agenda</a>
        <a href="../../views/admin/trabajadores.html">Trabajadores</a>
      </div>

      <!-- Formulario -->
      <div class="form-container">
        <h2>Editar Perfil Administrador</h2>
        <form id="formPerfil" class="form-grid">
          <input type="text" id="nombres" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($admin['nombre']) ?>" />
          <input type="text" id="apellidos" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($admin['apellido']) ?>" />
          <input type="email" id="correo" name="correo" placeholder="Correo electrónico" value="<?= htmlspecialchars($admin['correo']) ?>" />
          <input type="tel" id="telefono" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($admin['telefono']) ?>" />

          <div class="form-buttons">
            <button type="button" id="btnGuardar" class="btn-save">Guardar Cambios</button>
            <button type="button" id="btnEliminar" class="btn-delete">Eliminar Cuenta</button>
          </div>
        </form>

    </div>
  </div>

  <script src="../../assets/js/editarPerfilAdmins.js"></script>
</body>
</html>