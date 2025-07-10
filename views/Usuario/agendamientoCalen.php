<<<<<<< HEAD
<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
require_once(__DIR__ . '/../../configuracion/conexion.php');

$pdo = conexion();

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT id_mascota, nombre, raza FROM mascotas WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$mascotas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/agendamientoCalendarios.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>Agendamiento - PetLittle</title>
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
    <div><div><?= htmlspecialchars($nombreUsuario) ?></div></div>
  </div>
</header>

<div class="container">
  <div class="left-panel">
    <h2>Seleccione la Especialidad</h2>
    <div class="specialty-list" id="specialtyList"></div>
    <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
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

      <div class="campo-formulario">
        <label for="mascota">Seleccione su mascota:</label>
        <select name="mascota" id="mascota" class="select-mascota" required>
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
</div>

<script>
let selectedDate = null;
let selectedSpecialty = null;
let horasOcupadas = [];

const diasSinServicio = [];
const calendarDates = document.getElementById('calendarDates');
const monthSelect = document.getElementById('month');
const yearSelect = document.getElementById('year');
const hourSelect = document.getElementById('hourSelect');

function calcularFestivosColombia(year) {
  const festivos = [`${year}-01-01`, `${year}-05-01`, `${year}-07-20`, `${year}-08-07`, `${year}-12-08`, `${year}-12-25`];
  const movibles = [`${year}-01-06`, `${year}-03-19`, `${year}-06-29`, `${year}-08-15`, `${year}-10-12`, `${year}-11-01`, `${year}-11-11`];
  const siguienteLunes = (fecha) => {
    const d = new Date(fecha);
    const day = d.getDay();
    d.setDate(d.getDate() + (day === 0 ? 1 : 8 - day));
    return d.toISOString().split('T')[0];
  };
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

function actualizarHorasDisponibles() {
  hourSelect.innerHTML = '';
  for (let h = 8; h <= 18; h++) {
    const hourStr = h.toString().padStart(2, '0');
    ["00", "30"].forEach(min => {
      if (h === 18 && min === "30") return;
      const horaCompleta = `${hourStr}:${min}`;
      const option = document.createElement('option');
      option.value = horaCompleta;
      option.textContent = horaCompleta;

      if (horasOcupadas.includes(horaCompleta)) {
        option.disabled = true;
        option.textContent += " (Ocupado)";
        option.style.color = "#888";
      }

      hourSelect.appendChild(option);
    });
  }
}

function renderCalendar(month, year) {
  calendarDates.innerHTML = '';
  const firstDay = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();
  for (let i = 0; i < firstDay; i++) calendarDates.innerHTML += `<div></div>`;
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
        if (selectedSpecialty) {
          cargarHorasOcupadas(selectedDate, selectedSpecialty);
        }
      });
    }
    calendarDates.appendChild(div);
  }
}

monthSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));
yearSelect.addEventListener('change', () => renderCalendar(+monthSelect.value, +yearSelect.value));

function cargarHorasOcupadas(dateObj, especialidad) {
  const fecha = dateObj.toISOString().split('T')[0];
  fetch(`../../controllers/horasOcupadas.php?fecha=${fecha}&especialidad=${encodeURIComponent(especialidad)}`)
    .then(res => res.json())
    .then(data => {
      horasOcupadas = data;
      actualizarHorasDisponibles();
    });
}

function loadEspecialidades() {
  fetch('../../controllers/especialidadesController.php?action=listar')
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('specialtyList');
      data.forEach(item => {
        const div = document.createElement('div');
        div.classList.add('specialty-item');
        div.innerHTML = `<img src="../../assets/img/${item.imagen}" /> ${item.nombre}`;
        div.addEventListener('click', () => {
          document.querySelectorAll('.specialty-item').forEach(d => d.classList.remove('selected'));
          div.classList.add('selected');
          selectedSpecialty = item.nombre;
          if (selectedDate) {
            cargarHorasOcupadas(selectedDate, selectedSpecialty);
          }
        });
        container.appendChild(div);
      });
    });
}

document.getElementById('agendar').addEventListener('click', () => {
  if (!selectedSpecialty) return Swal.fire('Selecciona una especialidad', '', 'warning');
  if (!selectedDate) return Swal.fire('Selecciona una fecha', '', 'warning');
  const idMascota = document.getElementById('mascota').value;
  if (!idMascota) return Swal.fire('Selecciona una mascota', '', 'warning');

  const fechaCompleta = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')} ${hourSelect.value}`;

  fetch('../../controllers/guardarCita.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ especialidad: selectedSpecialty, fecha_hora: fechaCompleta, id_mascota: idMascota })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      Swal.fire('Cita agendada', `Tu cita de ${selectedSpecialty} fue registrada para el ${fechaCompleta.replace(' ', ' a las ')}`, 'success');
      cargarHorasOcupadas(selectedDate, selectedSpecialty); // actualizar después de agendar
    } else {
      Swal.fire('Error al guardar', res.error || 'Error desconocido', 'error');
    }
  });
});

loadEspecialidades();
renderCalendar(today.getMonth(), today.getFullYear());
</script>

<!-- ✅ Script para cerrar sesión por inactividad -->
<script>
let timeoutInactivity;
function cerrarSesionPorInactividad() {
  window.location.href = '../../models/logout.php';
}
function reiniciarTemporizador() {
  clearTimeout(timeoutInactivity);
  timeoutInactivity = setTimeout(cerrarSesionPorInactividad, 300000);
}
window.onload = reiniciarTemporizador;
document.onmousemove = reiniciarTemporizador;
document.onkeydown = reiniciarTemporizador;
document.onclick = reiniciarTemporizador;
</script>
</body>
</html>
=======
<?php
session_start();
$nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Invitado';
require_once(__DIR__ . '/../../configuracion/conexion.php');

$pdo = conexion();

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT id_mascota, nombre, raza FROM mascotas WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$mascotas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../assets/css/agendamientoCalendarios.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>Agendamiento - PetLittle</title>
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
    <div class="specialty-list" id="specialtyList"></div>
    <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
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

<div class="campo-formulario">
  <label for="mascota">Seleccione su mascota:</label>
  <select name="mascota" id="mascota" class="select-mascota" required>
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

<script>
let selectedDate = null;
let selectedSpecialty = null;
const diasSinServicio = [];
const calendarDates = document.getElementById('calendarDates');
const monthSelect = document.getElementById('month');
const yearSelect = document.getElementById('year');
const hourSelect = document.getElementById('hourSelect');

function calcularFestivosColombia(year) {
  const festivos = [
    `${year}-01-01`, `${year}-05-01`, `${year}-07-20`,
    `${year}-08-07`, `${year}-12-08`, `${year}-12-25`
  ];
  const movibles = [
    `${year}-01-06`, `${year}-03-19`, `${year}-06-29`,
    `${year}-08-15`, `${year}-10-12`, `${year}-11-01`, `${year}-11-11`
  ];
  const siguienteLunes = (fecha) => {
    const d = new Date(fecha);
    const day = d.getDay();
    d.setDate(d.getDate() + (day === 0 ? 1 : 8 - day));
    return d.toISOString().split('T')[0];
  };
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
  for (let i = 0; i < firstDay; i++) calendarDates.innerHTML += `<div></div>`;
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

function loadEspecialidades() {
  fetch('../../controllers/especialidadesController.php?action=listar')
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('specialtyList');
      data.forEach(item => {
        const div = document.createElement('div');
        div.classList.add('specialty-item');
        div.innerHTML = `<img src="../../assets/img/${item.imagen}" /> ${item.nombre}`;
        div.addEventListener('click', () => {
          document.querySelectorAll('.specialty-item').forEach(d => d.classList.remove('selected'));
          div.classList.add('selected');
          selectedSpecialty = item.nombre;
        });
        container.appendChild(div);
      });
    });
}

loadEspecialidades();
renderCalendar(today.getMonth(), today.getFullYear());

document.getElementById('agendar').addEventListener('click', () => {
  if (!selectedSpecialty) return Swal.fire('Selecciona una especialidad', '', 'warning');
  if (!selectedDate) return Swal.fire('Selecciona una fecha', '', 'warning');
  const idMascota = document.getElementById('mascota').value;
  if (!idMascota) return Swal.fire('Selecciona una mascota', '', 'warning');

  const fechaCompleta = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')} ${hourSelect.value}`;

  fetch('../../controllers/guardarCita.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ especialidad: selectedSpecialty, fecha_hora: fechaCompleta, id_mascota: idMascota })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      Swal.fire('Cita agendada', `Tu cita de ${selectedSpecialty} fue registrada para el ${fechaCompleta.replace(' ', ' a las ')}`, 'success');
    } else {
      Swal.fire('Error al guardar', res.error || 'Error desconocido', 'error');
    }
  });
});
</script>
</body>
</html>
>>>>>>> f09693777529f8988286f9f474767640441d8127
