<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../login/login.php");
        exit;
    }

    $usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../../assets/css/estilosPerfil.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="logo-container">
        <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
    </div>
    <h1 class="titulo-header">Panel de Administrador</h1>
</header>

<div class="contenedor">
    
    <div class="perfil-sobre-menu">
         <p class="nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p class="rol">Administrador</p>
        <a href="../admin/editarPerfil.html">Editar información</a>
    </div>

    <aside class="menu-lateral">
        <nav class="menu">
            <a href="#">Inicio</a>
            <a href="#">Notificaciones</a>
            <a href="../../models/logout.php" class="cerrar-sesion">Cerrar Sesión</a>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="contenido">
      <h2>Bienvenido</h2>
      <p>Accede a las herramientas de gestión para organizar mejor la atención de los pacientes.</p>

      <div class="opciones">
          <div class="opcion">
              <h3>Gestión de Clientes</h3>
              <button onclick="window.location.href='usuarios.html'">Ingresar</button>
          </div>
          <div class="opcion">
              <h3>Gráficas</h3>
              <button onclick="window.location.href='graficos.html'">Ingresar</button>
          </div>
          <div class="opcion">
              <h3>Agenda</h3>
              <button onclick="window.location.href='vista.html'">Ingresar</button>
          </div>
          <div class="opcion">
              <h3>Trabajadores</h3>
              <button onclick="window.location.href='trabajadores.php'">Ingresar</button>
          </div>
      </div>
  </main>
  </div>
</body>
</html>