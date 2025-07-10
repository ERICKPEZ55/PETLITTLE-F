<?php
session_start();
require_once('../../configuracion/conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../models/login.php");
    exit;
}

$pdo = conexion();
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT m.id_mascota, m.nombre AS nombre_mascota, m.raza, m.sexo, m.imagen, u.nombre AS nombre_duenio, u.apellido
        FROM mascotas m
        INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE m.id_usuario = :id_usuario";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión de Mascotas</title>
  <link rel="stylesheet" href="../../assets/css/misMascotas.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header class="header">
    <div class="logo-container">
      <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
    </div>
    <h1 class="titulo-header">Mis Mascotas</h1>
  </header>

  <div class="container">
    <button class="boton-nueva-mascota" id="btnNuevaMascota">+ Nueva Mascota</button>
    <div class="mascotas-grid" id="mascotas">
      <?php foreach ($mascotas as $mascota): ?>
        <div class="mascota-card">
          <img src="../../assets/img/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>" alt="<?= htmlspecialchars($mascota['nombre_mascota']) ?>">
          <div class="info-container">
            <h3><?= htmlspecialchars($mascota['nombre_mascota']) ?></h3>
            <p class="raza"><i class="fas fa-paw"></i> Raza: <?= htmlspecialchars($mascota['raza']) ?></p>
            <p class="sexo"><i class="fas fa-venus-mars"></i> Género: <?= htmlspecialchars($mascota['sexo']) ?></p>
            <p class="dueño"><i class="fas fa-user"></i> Dueño: <?= htmlspecialchars($mascota['nombre_duenio']) . ' ' . htmlspecialchars($mascota['apellido']) ?></p>
          </div>
          <div class="acciones">
            <button class="btn-ver-perfil" onclick="window.location.href='perfilMascotaUsuario.php?id_mascota=<?= $mascota['id_mascota'] ?>'">Ver Perfil</button>
            <button class="btn-eliminar" onclick="eliminarMascota(<?= $mascota['id_mascota'] ?>)">Eliminar</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Modal de Registro -->
  <div id="modalRegistro" class="modal">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <h2>Registrar Nueva Mascota</h2>
      <form id="formularioRegistro" action="../../controllers/guardarMascota.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required
        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" 
        maxlength="15"
        title="Solo letras y espacios. Máximo 15 caracteres.">

        <label for="raza">Raza:</label>
        <input type="text" id="raza" name="raza" required
        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" 
        maxlength="30"
        title="Solo letras y espacios. Máximo 30 caracteres.">

        <label for="genero">Género:</label>
        <select id="sexo" name="sexo" required>
          <option value="">Seleccione una opción</option>
          <option value="Macho">Macho</option>
          <option value="Hembra">Hembra</option>
          <option value="Desconocido">Desconocido</option>
        </select>

        <label for="imagenArchivo">Imagen:</label>
        <input type="file" id="imagenArchivo" name="imagen" accept="image/*" required>

        <br><br>
        <button type="submit" name="registrar">Registrar</button>
      </form>
    </div>
  </div>

  <div style="text-align: center;">
    <button class="navigation back-button" onclick="window.location.href='agendamiento.php'">← Volver</button>
  </div>

  <script>
  document.getElementById('formularioRegistro').addEventListener('submit', function (e) {
    const nombre = document.getElementById('nombre').value.trim();
    const raza = document.getElementById('raza').value.trim();
    const regexLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

    if (!regexLetras.test(nombre)) {
      e.preventDefault();
      Swal.fire('Error', 'El nombre solo debe contener letras y espacios.', 'error');
      return;
    }

    if (!regexLetras.test(raza)) {
      e.preventDefault();
      Swal.fire('Error', 'La raza solo debe contener letras y espacios.', 'error');
      return;
    }

    if (nombre.length > 30 || raza.length > 30) {
      e.preventDefault();
      Swal.fire('Error', 'Nombre y raza deben tener máximo 30 caracteres.', 'error');
      return;
    }

    const imagen = document.getElementById('imagenArchivo');
    const archivo = imagen.files[0];
    const formatosValidos = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!formatosValidos.includes(archivo.type)) {
      e.preventDefault();
      Swal.fire('Error', 'La imagen debe ser JPG o PNG.', 'error');
      return;
    }

  });
</script>


  <script>
    const modal = document.getElementById("modalRegistro");
    const btnNuevaMascota = document.getElementById("btnNuevaMascota");
    const spanCerrar = document.querySelector(".close-button");

    btnNuevaMascota.onclick = () => modal.style.display = "block";
    spanCerrar.onclick = () => modal.style.display = "none";
    window.onclick = event => {
      if (event.target === modal) modal.style.display = "none";
    };

    function eliminarMascota(id) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará permanentemente la mascota',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../../controllers/eliminarMascota.php?id=" + id;
        }
      });
    }

    <?php if (isset($_SESSION['alerta'])): ?>
      Swal.fire({
        icon: '<?= $_SESSION['alerta']['tipo'] ?>',
        title: '<?= $_SESSION['alerta']['tipo'] === "success" ? "Éxito" : "Error" ?>',
        text: '<?= $_SESSION['alerta']['mensaje'] ?>'
      });
      <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>
  </script>

  <!-- Script para cerrar sesión tras inactividad -->
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
