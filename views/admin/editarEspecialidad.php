<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Gestión de Especialidades</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/editarEspecialidad.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header>
    <div class="logo-container">
        <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
    </div>
    <h1>Gestión de Especialidades</h1>
    <button class="btn-nueva" onclick="abrirModal()">+ Nueva Especialidad</button>
  </header>
  <div class="contenedor" id="contenedorEspecialidades">
    <!-- Especialidades se cargarán dinámicamente con JS -->
    <a href="perfilAdmin.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
  </div>

  <div class="modal" id="modal">
    <div class="modal-content">
      <span class="cerrar" onclick="cerrarModal()">&times;</span>
      <h2>Agregar / Editar Especialidad</h2>
      <input type="hidden" id="id_especialidad">
      <input type="text" id="nombre" placeholder="Nombre de la especialidad">
      <label for="archivoImagen">Imagen (.png)</label>
      <input type="file" id="archivoImagen" accept=".png">
      <button class="btn-guardar" onclick="guardarEspecialidad()">Guardar</button>
    </div>
  </div>

  <script>
    async function cargarEspecialidades() {
      const res = await fetch('../../controllers/especialidadesController.php?action=listar');
      const data = await res.json();
      const contenedor = document.getElementById('contenedorEspecialidades');

      contenedor.innerHTML = data.map(e => `
        <div class="especialidad-item">
          <div class="info">
            <img src="../../assets/img/${e.imagen}" alt="${e.nombre}">
            <div class="nombre-especialidad">${e.nombre}</div>
          </div>
          <div class="acciones">
            <button onclick="abrirModal('${e.nombre}', ${e.id_especialidad})">✏️</button>
            <button onclick="eliminarEspecialidad(${e.id_especialidad})">🗑️</button>
          </div>
        </div>
      `).join('') + contenedor.querySelector('#btnVolver').outerHTML;
    }

    function abrirModal(nombre = '', id = '') {
      document.getElementById('modal').style.display = 'flex';
      document.getElementById('id_especialidad').value = id;
      document.getElementById('nombre').value = nombre;
      document.getElementById('archivoImagen').value = '';
    }

    function cerrarModal() {
      document.getElementById('modal').style.display = 'none';
    }

    async function guardarEspecialidad() {
      const id = document.getElementById('id_especialidad').value;
      const nombre = document.getElementById('nombre').value;
      const archivo = document.getElementById('archivoImagen').files[0];

      if (nombre.trim() === '') {
        return Swal.fire('Error', 'El nombre es obligatorio.', 'warning');
      }

      const formData = new FormData();
      formData.append('nombre', nombre);
      formData.append('action', id ? 'actualizar' : 'crear');
      if (id) formData.append('id_especialidad', id);
      if (archivo) formData.append('imagen', archivo);

      const res = await fetch('../../controllers/especialidadesController.php', {
        method: 'POST',
        body: formData
      });

      const result = await res.json();
      Swal.fire(result.status, result.message, result.status);
      cerrarModal();
      cargarEspecialidades();
    }

    async function eliminarEspecialidad(id) {
      const confirm = await Swal.fire({
        title: 'Eliminar especialidad?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Eliminar'
      });

      if (confirm.isConfirmed) {
        const res = await fetch(`../../controllers/especialidadesController.php?action=eliminar&id=${id}`);
        const result = await res.json();
        Swal.fire(result.status, result.message, result.status);
        cargarEspecialidades();
      }
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

    document.addEventListener('DOMContentLoaded', () => {
    cargarEspecialidades();
    reiniciarTemporizador();
  });


    
    document.onmousemove = reiniciarTemporizador;
    document.onkeydown = reiniciarTemporizador;
    document.onclick = reiniciarTemporizador;
  </script>
</body>
</html>
