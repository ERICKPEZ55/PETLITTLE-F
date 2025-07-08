document.addEventListener("DOMContentLoaded", () => {
  cargarEspecialidades(); // NUEVO: cargar solo especialidades vigentes
  cargarCitas();

  document.getElementById("cerrarAgregar").addEventListener("click", cerrarModal);
  document.getElementById("guardarCita").addEventListener("click", actualizarCita);

  window.addEventListener("click", (event) => {
    const modal = document.getElementById("modalAgregar");
    if (event.target === modal) cerrarModal();
  });
});

async function cargarCitas() {
  const res = await fetch("../../controllers/citasController.php?action=listar");
  const data = await res.json();
  const tbody = document.getElementById("tablaCitas");
  tbody.innerHTML = "";

  data.forEach(cita => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${cita.nombre_usuario}</td>
      <td>${cita.correo}</td>
      <td>${cita.telefono}</td>
      <td>${cita.nombre_mascota}</td>
      <td>${cita.nombre_especialidad}</td>
      <td>${formatearFechaHora(cita.fecha_hora)}</td>
      <td>
        <button class="btn-editar" 
                data-id="${cita.id_cita}" 
                data-especialidad="${encodeURIComponent(cita.nombre_especialidad)}"
                data-fecha="${cita.fecha_hora}">
          ✏️
        </button>
      </td>
      <td>
        <button class="btn-cancelar" data-id="${cita.id_cita}">🗑️</button>
      </td>
    `;

    tbody.appendChild(fila);
  });

  // Eventos dinámicos
  document.querySelectorAll(".btn-editar").forEach(boton => {
    boton.addEventListener("click", () => {
      abrirEditar(
        boton.dataset.id,
        decodeURIComponent(boton.dataset.especialidad),
        boton.dataset.fecha
      );
    });
  });

  document.querySelectorAll(".btn-cancelar").forEach(boton => {
    boton.addEventListener("click", () => {
      cancelarCita(boton.dataset.id);
    });
  });
}

function abrirEditar(idCita, especialidad, fechaHora) {
  document.getElementById("tituloModal").textContent = "Editar Cita";
  document.getElementById("modalAgregar").style.display = "block";

  // Deshabilitados
  document.getElementById("nombreCliente").value = "No editable";
  document.getElementById("correoCliente").value = "No editable";
  document.getElementById("telefonoCliente").value = "No editable";
  document.getElementById("nombreMascota").value = "No editable";

  document.getElementById("tipoCita").value = especialidad;
  document.getElementById("fechaHora").value = formatoInputFecha(fechaHora);

  document.getElementById("guardarCita").dataset.id = idCita;
}

async function actualizarCita() {
  const idCita = this.dataset.id;
  const especialidad = document.getElementById("tipoCita").value;
  const fechaHora = document.getElementById("fechaHora").value;

  if (!especialidad || !fechaHora) {
    Swal.fire("Campos incompletos", "Debes seleccionar la especialidad y la fecha/hora", "warning");
    return;
  }

  const btnGuardar = document.getElementById("guardarCita");
  btnGuardar.disabled = true;

  const datos = {
    action: "actualizar",
    id_cita: idCita,
    especialidad: especialidad,
    fecha_hora: fechaHora
  };

  try {
    const res = await fetch("../../controllers/citasController.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(datos)
    });

    const result = await res.json();

    Swal.fire({
      title: result.success ? 'Cita actualizada' : 'Error al actualizar',
      text: result.message,
      icon: result.success ? 'success' : 'error'
    });

    cerrarModal();
    cargarCitas();

  } catch (error) {
    Swal.fire("Error", "Ocurrió un error al actualizar la cita", "error");
  } finally {
    btnGuardar.disabled = false;
  }
}

function cancelarCita(idCita) {
  Swal.fire({
    title: '¿Cancelar esta cita?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`../../controllers/citasController.php?action=eliminar&id=${idCita}`)
        .then(res => res.json())
        .then(data => {
          Swal.fire(
            data.success ? 'Cancelada' : 'Error',
            data.message,
            data.success ? 'success' : 'error'
          );
          cargarCitas();
        });
    }
  });
}

function cerrarModal() {
  document.getElementById("modalAgregar").style.display = "none";
  document.getElementById("tipoCita").value = "";
  document.getElementById("fechaHora").value = "";
}

// ✅ NUEVO: cargar especialidades activas desde la base de datos
async function cargarEspecialidades() {
  try {
    const res = await fetch("../../controllers/obtenerEspecialidades.php");
    const lista = await res.json();
    const select = document.getElementById("tipoCita");

    select.innerHTML = ""; // Limpiar select

    lista.forEach(nombre => {
      const opt = document.createElement("option");
      opt.value = nombre;
      opt.textContent = nombre;
      select.appendChild(opt);
    });
  } catch (error) {
    console.error("Error cargando especialidades:", error);
  }
}

function formatearFechaHora(fechaHora) {
  const fecha = new Date(fechaHora);
  const opciones = {
    year: "numeric", month: "2-digit", day: "2-digit",
    hour: "2-digit", minute: "2-digit"
  };
  return fecha.toLocaleString("es-CO", opciones);
}

function formatoInputFecha(fechaHora) {
  const f = new Date(fechaHora);
  const yyyy = f.getFullYear();
  const mm = String(f.getMonth() + 1).padStart(2, '0');
  const dd = String(f.getDate()).padStart(2, '0');
  const hh = String(f.getHours()).padStart(2, '0');
  const mi = String(f.getMinutes()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
}
