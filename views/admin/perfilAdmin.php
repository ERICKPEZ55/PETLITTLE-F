<?php
session_start();

// Evitar que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//Verifica que haya sesión activa
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
  <link rel="stylesheet" href="../../assets/css/vistaAdminDashboard.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </div>

    <aside class="menu-lateral">
        <nav class="menu">
            <a href="../admin/editarPerfil.php">Editar información</a>
            <a href="../admin/editarEspecialidad.php">Especialidades</a>
            <a href="#" class="cerrar-sesion" id="cerrarSesion">Cerrar Sesión</a>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="contenido">
      <h2>Bienvenido</h2>
      <p>Accede a las herramientas de gestión para organizar mejor la atención de los pacientes.</p>

      <div class="opciones">
        <div class="opcion">
            <h3>Gestión de Clientes</h3>
            <button onclick="window.location.href='usuarios.php'">Ingresar</button>
        </div>
        <div class="opcion">
            <h3>Gráficas</h3>
            <button onclick="window.location.href='graficos.php'">Ingresar</button>
        </div>
        <div class="opcion">
            <h3>Agenda</h3>
            <button onclick="window.location.href='vista.php'">Ingresar</button>
        </div>
        <div class="opcion">
            <h3>Trabajadores</h3>
            <button onclick="window.location.href='trabajadores.php'">Ingresar</button>
        </div>
      </div>
    </main>
  </div>

  <!--  SweetAlert2 para confirmar cierre de sesión -->
  <script>
    document.getElementById("cerrarSesion").addEventListener("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro de que quieres salir?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../models/logout.php';
            }
        });
    });
  </script>
</body>
</html>
