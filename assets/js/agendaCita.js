const fechaElemento = document.getElementById('fechaTexto');
const btnAnterior = document.getElementById('btnAnterior');
const btnSiguiente = document.getElementById('btnSiguiente');
const timeGrid = document.querySelector('.time-grid');

let fechaActual = new Date(2025, 4, 27); // 27 mayo 2025

const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

const citasPorFecha = {
  '2025-05-27': [
    { hora: '09:00', tipo: 'Consulta General', mascota: 'Luna', duenio: 'Marta Pérez', duracion: '20 min', estado: 'Pendiente' },
    { hora: '09:40', tipo: 'Vacunación', mascota: 'Rocky', duenio: 'Sofía Vargas', duracion: '20 min', estado: 'Confirmada' },
    { hora: '10:20', tipo: 'Control postoperatorio', mascota: 'Kiara', duenio: 'Mateo León', duracion: '20 min', estado: 'Completada' }
  ],
  '2025-05-28': [
    { hora: '09:00', tipo: 'Desparasitación', mascota: 'Simba', duenio: 'Andrés Castro', duracion: '20 min', estado: 'Pendiente' },
    { hora: '10:00', tipo: 'Consulta General', mascota: 'Lola', duenio: 'Daniela Flores', duracion: '20 min', estado: 'Confirmada' }
  ]
};

function formatearFechaClave(fecha) {
  return fecha.toISOString().split('T')[0];
}

function actualizarFecha() {
  const diaSemana = diasSemana[fechaActual.getDay()];
  const dia = fechaActual.getDate();
  const mes = meses[fechaActual.getMonth()];
  const anio = fechaActual.getFullYear();
  fechaElemento.textContent = `${diaSemana}, ${dia} ${mes} ${anio}`;
  cargarCitasDelDia();
}

function cambiarFecha(dias) {
  fechaActual.setDate(fechaActual.getDate() + dias);
  actualizarFecha();
}

function cargarCitasDelDia() {
  const claveFecha = formatearFechaClave(fechaActual);
  const citas = citasPorFecha[claveFecha] || [];

  const horas = ['09:00', '09:20', '09:40', '10:00', '10:20', '10:40'];
  timeGrid.innerHTML = ''; // Limpiar todo

  horas.forEach(hora => {
    // Crear slot vacío
    const slot = document.createElement('div');
    slot.classList.add('time-slot');
    slot.innerHTML = `<span>${hora}</span>`;
    timeGrid.appendChild(slot);

    // Si hay cita en esa hora
    const cita = citas.find(c => c.hora === hora);
    if (cita) {
      const card = document.createElement('div');
      card.classList.add('time-slot', 'service-card', cita.estado.toLowerCase());
      card.innerHTML = `
        <div class="info">
          <h3>${cita.tipo} - ${cita.mascota}</h3>
          <p>Dueño: ${cita.duenio}</p>
          <p>Duración: ${cita.duracion}</p>
        </div>
        <div class="estado">
          <label>Estado:</label>
          <select>
            <option${cita.estado === 'Pendiente' ? ' selected' : ''}>Pendiente</option>
            <option${cita.estado === 'Confirmada' ? ' selected' : ''}>Confirmada</option>
            <option${cita.estado === 'Completada' ? ' selected' : ''}>Completada</option>
            <option${cita.estado === 'Cancelada' ? ' selected' : ''}>Cancelada</option>
          </select>
        </div>
      `;
      timeGrid.appendChild(card);
    }
  });
}

// Conexión de botones
btnAnterior.addEventListener('click', () => cambiarFecha(-1));
btnSiguiente.addEventListener('click', () => cambiarFecha(1));

document.addEventListener('DOMContentLoaded', actualizarFecha);
