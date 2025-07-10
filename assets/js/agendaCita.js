<<<<<<< HEAD
const fechaElemento = document.getElementById('fechaTexto');
const btnAnterior = document.getElementById('btnAnterior');
const btnSiguiente = document.getElementById('btnSiguiente');
const timeGrid = document.querySelector('.time-grid');

// Fecha dinámica: hoy
let fechaActual = new Date();

const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function formatearFechaClave(fecha) {
  // Formato YYYY-MM-DD sin convertir a UTC
  const anio = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // +1 porque enero es 0
  const dia = String(fecha.getDate()).padStart(2, '0');
  return `${anio}-${mes}-${dia}`;
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
  const horas = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];
  timeGrid.innerHTML = ''; // Limpiar todo

  fetch(`../../controllers/agendaVeterinario.php?fecha=${claveFecha}`)
    .then(res => res.json())
    .then(citas => {
      horas.forEach(hora => {
        // Crear slot vacío
        const slot = document.createElement('div');
        slot.classList.add('time-slot');
        slot.innerHTML = `<span>${hora}</span>`;
        timeGrid.appendChild(slot);

        // Buscar cita en esa hora
        const cita = citas.find(c => c.fecha_hora.slice(11, 16) === hora);
        if (cita) {
          const card = document.createElement('div');
          card.classList.add('time-slot', 'service-card');

          // Crear el <select> dinámicamente
          const select = document.createElement('select');
          const estados = ['Asistió', 'No asistió', 'Cancelada'];
          select.innerHTML = estados.map(e =>
            `<option${cita.estado === e ? ' selected' : ''}>${e}</option>`
          ).join('');

          // Evento: actualizar estado al cambiar
          select.addEventListener('change', () => {
            const nuevoEstado = select.value;

            fetch('../../controllers/actualizarEstadoCita.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id_cita=${encodeURIComponent(cita.id_cita)}&estado=${encodeURIComponent(nuevoEstado)}`
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                console.log('Estado actualizado');
              } else {
                alert('Error al actualizar el estado');
              }
            })
            .catch(err => {
              console.error('Error en la solicitud:', err);
              alert('Error al conectar con el servidor');
            });
          });

          card.innerHTML = `
          <div class="info">
            <h3>${cita.especialidad}</h3>
            <p>Dueño: ${cita.nombre}</p>
            <p>Paciente: ${cita.nombreM}</p>
            <p>Duración: ${cita.duracion} min</p>
          </div>
          <div class="estado">
            <label>Estado:</label>
          </div>
        `;

          card.querySelector('.estado').appendChild(select);
          timeGrid.appendChild(card);
        }
      });
    })
    .catch(error => {
      console.error('Error al cargar las citas:', error);
      timeGrid.innerHTML = '<p style="color:red;">Error al cargar las citas.</p>';
    });
}

// Botones de navegación
btnAnterior.addEventListener('click', () => cambiarFecha(-1));
btnSiguiente.addEventListener('click', () => cambiarFecha(1));

// Al cargar la página
document.addEventListener('DOMContentLoaded', actualizarFecha);
=======
const fechaElemento = document.getElementById('fechaTexto');
const btnAnterior = document.getElementById('btnAnterior');
const btnSiguiente = document.getElementById('btnSiguiente');
const timeGrid = document.querySelector('.time-grid');

// Fecha dinámica: hoy
let fechaActual = new Date();

const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function formatearFechaClave(fecha) {
  // Formato YYYY-MM-DD sin convertir a UTC
  const anio = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // +1 porque enero es 0
  const dia = String(fecha.getDate()).padStart(2, '0');
  return `${anio}-${mes}-${dia}`;
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
  const horas = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];
  timeGrid.innerHTML = ''; // Limpiar todo

  fetch(`../../controllers/agendaVeterinario.php?fecha=${claveFecha}`)
    .then(res => res.json())
    .then(citas => {
      horas.forEach(hora => {
        // Crear slot vacío
        const slot = document.createElement('div');
        slot.classList.add('time-slot');
        slot.innerHTML = `<span>${hora}</span>`;
        timeGrid.appendChild(slot);

        // Buscar cita en esa hora
        const cita = citas.find(c => c.fecha_hora.slice(11, 16) === hora);
        if (cita) {
          const card = document.createElement('div');
          card.classList.add('time-slot', 'service-card');

          // Crear el <select> dinámicamente
          const select = document.createElement('select');
          const estados = ['Asistió', 'No asistió', 'Cancelada'];
          select.innerHTML = estados.map(e =>
            `<option${cita.estado === e ? ' selected' : ''}>${e}</option>`
          ).join('');

          // Evento: actualizar estado al cambiar
          select.addEventListener('change', () => {
            const nuevoEstado = select.value;

            fetch('../../controllers/actualizarEstadoCita.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id_cita=${encodeURIComponent(cita.id_cita)}&estado=${encodeURIComponent(nuevoEstado)}`
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                console.log('Estado actualizado');
              } else {
                alert('Error al actualizar el estado');
              }
            })
            .catch(err => {
              console.error('Error en la solicitud:', err);
              alert('Error al conectar con el servidor');
            });
          });

          card.innerHTML = `
          <div class="info">
            <h3>${cita.especialidad}</h3>
            <p>Dueño: ${cita.nombre}</p>
            <p>Paciente: ${cita.nombreM}</p>
            <p>Duración: ${cita.duracion} min</p>
          </div>
          <div class="estado">
            <label>Estado:</label>
          </div>
        `;

          card.querySelector('.estado').appendChild(select);
          timeGrid.appendChild(card);
        }
      });
    })
    .catch(error => {
      console.error('Error al cargar las citas:', error);
      timeGrid.innerHTML = '<p style="color:red;">Error al cargar las citas.</p>';
    });
}

// Botones de navegación
btnAnterior.addEventListener('click', () => cambiarFecha(-1));
btnSiguiente.addEventListener('click', () => cambiarFecha(1));

// Al cargar la página
document.addEventListener('DOMContentLoaded', actualizarFecha);
>>>>>>> f09693777529f8988286f9f474767640441d8127
