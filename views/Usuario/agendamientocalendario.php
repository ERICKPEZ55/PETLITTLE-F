<?php
  session_start();
  $nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
  require_once(__DIR__ . '/../../configuracion/conexion.php');

  $pdo = conexion();

  // Validar que el usuario esté logueado
  if (!isset($_SESSION['id_usuario'])) {
      die("Acceso denegado");
  }

  $id_usuario = $_SESSION['id_usuario'];

  // Traer mascotas del usuario
  $stmt = $pdo->prepare("SELECT id_mascota, nombre, raza FROM mascotas WHERE id_usuario = ?");
  $stmt->execute([$id_usuario]);
  $mascotas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/agendamientoCalendario.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>Agendamiento - PetLittle</title>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
  <div class="logo">
    <a href="../usuario/agendamiento.php">
      <img src="../../assets/img/logo negativo.png" alt="PetLittle" />
    </a>
  </div>
  <div class="title">AGENDAMIENTO</div>
  <div class="user-section">
    <input type="file" id="fileInput" class="hidden-file" accept="image/*" />
    <div>
      <div><?= htmlspecialchars($nombreUsuario) ?></div>
    </div>
  </div>
</header>

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
      <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
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

     <label for="mascota">Seleccione su mascota:</label>
     <select name="mascota" id="mascota" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($mascotas as $mascota): ?>
          <option value="<?= $mascota['id_mascota'] ?>">
            <?= htmlspecialchars($mascota['nombre']) ?> (<?= htmlspecialchars($mascota['raza']) ?>)
          </option>
        <?php endforeach; ?>
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
    const festivos = [
      `${year}-01-01`, `${year}-05-01`, `${year}-07-20`,
      `${year}-08-07`, `${year}-12-08`, `${year}-12-25`
    ];
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
        if (date.getDay() === 0 || date.getDay() === 6) {
          diasSinServicio.push(date.toISOString().split('T')[0]);
        }
      }
    }
  }

  const months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
  const today = new Date();

  for (let i = 0; i < 12; i++) {
    const option = document.createElement('option');
    option.value = i;
    option.textContent = months[i];
    if (i === today.getMonth()) option.selected = true;
    monthSelect.appendChild(option);
  }

  for (let i = today.getFullYear(); i <= today.getFullYear() + 5; i++) {
    const option = document.createElement('option');
    option.value = i;
    option.textContent = i;
    if (i === today.getFullYear()) option.selected = true;
    yearSelect.appendChild(option);
  }

  for (let h = 8; h <= 18; h++) {
    const hourStr = h.toString().padStart(2, '0');
    hourSelect.innerHTML += `<option value="${hourStr}:00">${hourStr}:00</option>`;
    if (h !== 18) hourSelect.innerHTML += `<option value="${hourStr}:30">${hourStr}:30</option>`;
  }

  function renderCalendar(month, year) {
    calendarDates.innerHTML = '';
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      calendarDates.innerHTML += `<div></div>`;
    }

    for (let day = 1; day <= lastDate; day++) {
      const fullDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const div = document.createElement('div');
      div.textContent = day;
      if (diasSinServicio.includes(fullDateStr)) {
        div.classList.add('no-service');
      } else {
        div.addEventListener('click', () => {
          document.querySelectorAll('.calendar-dates div').forEach(d => d.classList.remove('selected'));
          div.classList.add('selected');
          selectedDate = new Date(year, month, day);
        });
      }
      calendarDates.appendChild(div);
    }
  }

  monthSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));
  yearSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));

  document.getElementById('agendar').addEventListener('click', () => {
    if (!selectedSpecialty) {
      Swal.fire('Selecciona una especialidad', '', 'warning');
    } else if (!selectedDate) {
      Swal.fire('Selecciona una fecha', '', 'warning');
    } else {
      const fecha = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')} ${hourSelect.value}`;
      guardarCita(fecha);
    }
  });

  async function guardarCita(fechaCompleta) {
    const idMascota = document.getElementById('mascota').value;

    if (!idMascota) {
      Swal.fire('Selecciona una mascota', '', 'info');
      return;
    }

    const datos = {
      especialidad: selectedSpecialty,
      fecha_hora: fechaCompleta,
      id_mascota: idMascota
    };

    const respuesta = await fetch('../../controllers/guardarCita.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datos)
    });

    const resultado = await respuesta.json();
    if (resultado.success) {
      Swal.fire({
        title: 'Cita agendada',
        text: `Tu cita de ${selectedSpecialty} fue registrada para el ${fechaCompleta.replace(' ', ' a las ')}`,
        icon: 'success'
      });
    } else {
      Swal.fire('Error al guardar', resultado.error || 'Error desconocido', 'error');
    }
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
