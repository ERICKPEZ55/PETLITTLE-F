// controlCitas.js

document.addEventListener("DOMContentLoaded", () => {
  cargarCitas();

  document.getElementById("cerrarAgregar").addEventListener("click", () => {
    document.getElementById("modalAgregar").style.display = "none";
  });

  document.getElementById("guardarCita").addEventListener("click", actualizarCita);
});

async function cargarCitas() {
  const res = await fetch("../../controllers/obtenerCitas.php");
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
      <td>${cita.especialidad}</td>
      <td>${formatearFechaHora(cita.fecha_hora)}</td>
      <td>
        <button class="btn-editar" onclick="abrirEditar(${cita.id_cita}, '${cita.especialidad}', '${cita.fecha_hora}')">✏️</button>
      </td>
      <td>
        <button class="btn-cancelar" onclick="cancelarCita(${cita.id_cita})">🗑️</button>
      </td>
    `;

    tbody.appendChild(fila);
  });
}

function abrirEditar(idCita, especialidad, fechaHora) {
  document.getElementById("tituloModal").textContent = "Editar Cita";
  document.getElementById("modalAgregar").style.display = "block";

  document.getElementById("nombreCliente").value = "No editable";
  document.getElementById("correoCliente").value = "No editable";
  document.getElementById("telefonoCliente").value = "No editable";
  document.getElementById("nombreMascota").value = "No editable";
  document.getElementById("nombreCliente").disabled = true;
  document.getElementById("correoCliente").disabled = true;
  document.getElementById("telefonoCliente").disabled = true;
  document.getElementById("nombreMascota").disabled = true;

  document.getElementById("tipoCita").value = especialidad;
  document.getElementById("fechaHora").value = fechaHora.replace(" ", "T");

  document.getElementById("guardarCita").dataset.id = idCita;
}

async function actualizarCita() {
  const idCita = this.dataset.id;
  const datos = {
    action: "actualizar",
    id_cita: idCita,
    especialidad: document.getElementById("tipoCita").value,
    fecha_hora: document.getElementById("fechaHora").value
  };

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

  document.getElementById("modalAgregar").style.display = "none";
  cargarCitas();
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

function formatearFechaHora(fechaHora) {
  const fecha = new Date(fechaHora);
  const opciones = {
    year: "numeric", month: "2-digit", day: "2-digit",
    hour: "2-digit", minute: "2-digit"
  };
  return fecha.toLocaleString("es-CO", opciones);
}
