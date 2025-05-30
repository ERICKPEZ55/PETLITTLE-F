<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/agendamientoCalendario.css">
  <title>Agendamiento - PetLittle</title>
</head>
<body>

  <header>
    <div class="logo">
      <a href="../Usuario/agendamiento.php">
        <img src="../../assets/img/logo negativo.png" alt="PetLittle" />
      </a>
    </div>
    <div class="title">AGENDAMIENTO</div>
    <div class="user-section">
      <label for="fileInput">
        <img id="profilePic" src="../../assets/img/avatarusuario.png" alt="Perfil" />
      </label>
      <input type="file" id="fileInput" class="hidden-file" accept="image/*" />
      <div>
        <div><?= htmlspecialchars($nombreUsuario) ?></div>
      </div>
    </div>
  </header>

  <!-- El resto de tu HTML queda exactamente igual -->


  <div class="container">
    <div class="left-panel">
      <h2>Seleccione la Especialidad</h2>
      <div class="specialty-list">
        <div class="specialty-item"><img src="../../assets/img/icocardiologia.png" /> Cardiología</div>
        <div class="specialty-item"><img src="../../assets/img/iconutricion.png" /> Nutrición</div>
        <div class="specialty-item"><img src="../../assets/img/icodermatologia.png" /> Dermatología</div>
        <div class="specialty-item"><img src="../../assets/img/icoodontologia.png" /> Odontología</div>
        <div class="specialty-item"><img src="../../assets/img/iconeurologia.png" /> Neurología</div>
        <div class="specialty-item"><img src="../../assets/img/icoendocri.png" /> Endocrinología</div>
        <div class="specialty-item"><img src="../../assets/img/icomedicinageneral.png" /> Medicina General</div>
        <a href="../Usuario/agendamiento.php">← Volver</a>
      </div>
    </div>

    <div class="right-panel">
      <h2>Seleccione la fecha</h2>
      <div class="calendar-container">
        <div class="calendar-header">
          <div class="calendariotitulo">Calendario</div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <select id="hourSelect"></select>
            <select id="month"></select>
            <select id="year"></select>
          </div>
        </div>
        <div class="calendar-days">
          <div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div>
        </div>
        <div class="calendar-dates" id="calendarDates"></div>

        <label for="mascotaSelect">Seleccione su mascota:</label>
        <select id="mascotaSelect">
          <?= $opcionesMascotas ?>
        </select>
      </div>

      <button id="agendar">Agendar</button>
    </div>
  </div>

  <div id="successModal" class="modal-success">
    <span class="close-btn" onclick="closeModal()">×</span>
    <p></p>
  </div>

  <script>
    const profilePic = document.getElementById('profilePic');
    const fileInput = document.getElementById('fileInput');

    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        profilePic.src = URL.createObjectURL(file);
      }
    });

    const monthSelect = document.getElementById('month');
    const yearSelect = document.getElementById('year');
    const hourSelect = document.getElementById('hourSelect');
    const calendarDates = document.getElementById('calendarDates');
    let selectedDate = null;
    let selectedSpecialty = null;

    const diasSinServicio = [];

    function calcularFestivosColombia(year) {
      const festivos = [];

      const fijos = [
        `${year}-01-01`, `${year}-05-01`, `${year}-07-20`,
        `${year}-08-07`, `${year}-12-08`, `${year}-12-25`
      ];

      festivos.push(...fijos);

      const siguienteLunes = (fecha) => {
        const d = new Date(fecha);
        const day = d.getDay();
        const diff = day === 0 ? 1 : 8 - day;
        d.setDate(d.getDate() + diff);
        return d.toISOString().split('T')[0];
      };

      const movibles = [
        `${year}-01-06`, `${year}-03-19`, `${year}-06-29`,
        `${year}-08-15`, `${year}-10-12`, `${year}-11-01`,
        `${year}-11-11`
      ];

      movibles.forEach(f => festivos.push(siguienteLunes(f)));

      return festivos;
    }

    for (let year = 2025; year <= 2030; year++) {
      diasSinServicio.push(...calcularFestivosColombia(year));

      for (let month = 0; month < 12; month++) {
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        for (let day = 1; day <= daysInMonth; day++) {
          const date = new Date(year, month, day);
          const dayOfWeek = date.getDay();
          if (dayOfWeek === 0 || dayOfWeek === 6) {
            const iso = date.toISOString().split('T')[0];
            diasSinServicio.push(iso);
          }
        }
      }
    }

    const months = [
      "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
      "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    const today = new Date();

    for (let i = 0; i < 12; i++) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = months[i];
      if (i === today.getMonth()) option.selected = true;
      monthSelect.appendChild(option);
    }

    const startYear = today.getFullYear();
    const endYear = startYear + 5;

    for (let i = startYear; i <= endYear; i++) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = i;
      if (i === today.getFullYear()) option.selected = true;
      yearSelect.appendChild(option);
    }

    for (let h = 8; h <= 18; h++) {
      const hourStr = h.toString().padStart(2, '0');
      const option1 = document.createElement('option');
      option1.value = `${hourStr}:00`;
      option1.textContent = `${hourStr}:00`;
      hourSelect.appendChild(option1);
      if (h !== 18) {
        const option2 = document.createElement('option');
        option2.value = `${hourStr}:30`;
        option2.textContent = `${hourStr}:30`;
        hourSelect.appendChild(option2);
      }
    }

    function renderCalendar(month, year) {
      calendarDates.innerHTML = '';
      const firstDay = new Date(year, month, 1).getDay();
      const lastDate = new Date(year, month + 1, 0).getDate();

      for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement('div');
        calendarDates.appendChild(empty);
      }

      for (let day = 1; day <= lastDate; day++) {
        const dateEl = document.createElement('div');
        dateEl.textContent = day;

        const fullDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        if (diasSinServicio.includes(fullDateStr)) {
          dateEl.classList.add('no-service');
        } else {
          dateEl.addEventListener('click', () => {
            document.querySelectorAll('.calendar-dates div').forEach(d => d.classList.remove('selected'));
            dateEl.classList.add('selected');
            selectedDate = new Date(year, month, day);
          });
        }

        calendarDates.appendChild(dateEl);
      }
    }

    monthSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));
    yearSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));

    document.getElementById('agendar').addEventListener('click', () => {
      if (!selectedSpecialty) {
        alert('Por favor seleccione una especialidad.');
      } else if (!selectedDate) {
        alert('Por favor seleccione una fecha.');
      } else {
        const dia = selectedDate.getDate().toString().padStart(2, '0');
        const mes = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
        const anio = selectedDate.getFullYear();
        const hora = hourSelect.value;
        const fechaCompleta = `${anio}-${mes}-${dia} ${hora}`;
        guardarCita(fechaCompleta);
      }
    });

    async function guardarCita(fechaCompleta) {
      const idMascota = document.getElementById('mascotaSelect').value;

      if (!idMascota) {
        alert('Por favor seleccione una mascota.');
        return;
      }

      const datos = {
        especialidad: selectedSpecialty,
        fecha_hora: fechaCompleta,
        id_mascota: idMascota
      };

      const respuesta = await fetch('guardarCita.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      });

      const resultado = await respuesta.json();
      if (resultado.success) {
        const modal = document.getElementById('successModal');
        const fecha = new Date(fechaCompleta);
        const dia = fecha.getDate().toString().padStart(2, '0');
        const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
        const anio = fecha.getFullYear();
        const hora = fecha.toTimeString().substring(0, 5);
        const mensaje = `Su cita de ${selectedSpecialty} se agendó para el día ${dia}/${mes}/${anio} a las ${hora}`;
        modal.querySelector('p').innerHTML = mensaje;
        modal.style.display = 'block';
      } else {
        alert("Error al guardar: " + resultado.error);
      }
    }

    function closeModal() {
      document.getElementById('successModal').style.display = 'none';
    }

    const specialtyItems = document.querySelectorAll('.specialty-item');
    specialtyItems.forEach(item => {
      item.addEventListener('click', () => {
        specialtyItems.forEach(i => i.classList.remove('selected'));
        item.classList.add('selected');
        selectedSpecialty = item.textContent.trim();
      });
    });

    renderCalendar(today.getMonth(), today.getFullYear());
  </script>
</body>
</html>
