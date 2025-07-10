<<<<<<< HEAD
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Conexión a la base de datos
require __DIR__ . '/../../configuracion/conexion.php';
$conn = conexion();

// Consulta de estadísticas
$stmtUsuarios = $conn->query("SELECT COUNT(*) FROM usuarios");
$totalUsuarios = $stmtUsuarios->fetchColumn();

// Contar todas las citas que NO estén canceladas
$stmtCitas = $conn->query("SELECT COUNT(*) FROM citas WHERE estado != 'Cancelada'");
$totalCitasAgendadas = $stmtCitas->fetchColumn();

// Contar solo las canceladas
$stmtCanceladas = $conn->query("SELECT COUNT(*) FROM citas WHERE estado = 'Cancelada'");
$totalCitasCanceladas = $stmtCanceladas->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agendamiento Administrador</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/vistaAdmin.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <style>
    .calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      text-align: center;
      font-weight: bold;
      color: #1b1b4d;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="logo-container">
        <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
    </div>
    <h1>Agenda Administrador</h1>
    <div class="admin-info">
      <p class="nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
    </div>
  </div>

  <div class="panel">
    <div class="panel-izquierda">
      <div class="panel-card">
        <div class="panel-title">Usuarios registrados</div>
        <p style="text-align:center;"><?php echo $totalUsuarios; ?> usuarios</p>
      </div>

      <div class="panel-card">
        <div class="panel-title">Citas agendadas</div>
        <p style="text-align:center;"><?php echo $totalCitasAgendadas; ?> citas</p>
      </div>

      <div class="panel-card">
        <div class="panel-title">Citas canceladas</div>
        <p style="text-align:center;"><?php echo $totalCitasCanceladas; ?> canceladas</p>
      </div>
        <div>
      <a href="../admin/perfilAdmin.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
      </div>
    </div>

    <div class="panel-derecha">
      <div class="calendar-container">
        <div class="calendar-header">
          <select id="month"></select>
          <select id="year"></select>
        </div>

        <div class="calendar-days">
          <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
        </div>

        <div id="calendar" class="calendar-grid"></div>

        <div class="calendar-legend" id="calendar-legend">Seleccione un día para ver detalles</div>
      </div>
    </div>
  </div>

 <script>
  const calendar = document.getElementById("calendar");
  const monthSelect = document.getElementById("month");
  const yearSelect = document.getElementById("year");
  const legend = document.getElementById("calendar-legend");

  const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

  for (let i = 0; i < months.length; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.text = months[i];
    monthSelect.appendChild(option);
  }

  const currentYear = new Date().getFullYear();
  for (let i = currentYear - 5; i <= currentYear + 5; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.text = i;
    yearSelect.appendChild(option);
  }

  function renderCalendar(month, year) {
    calendar.innerHTML = "";
    const firstDay = new Date(year, month).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    // 1️⃣ Primero traemos las fechas con citas
    fetch(`diasConCitas.php?mes=${month + 1}&anio=${year}`)
      .then(response => response.json())
      .then(fechas => {
        const fechasConCitas = new Set(fechas); // Para buscar rápidamente

        for (let i = 0; i < firstDay; i++) {
          calendar.innerHTML += '<div class="calendar-cell"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const cell = document.createElement("div");
          cell.className = "calendar-cell";

          const fechaStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

          // Marca el día actual
          if (
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
          ) {
            cell.classList.add("today");
          }

          // 2️⃣ Si ese día tiene citas, lo marcamos
          if (fechasConCitas.has(fechaStr)) {
            cell.classList.add("con-cita"); // clase para fondo azul
          }

          cell.textContent = day;

          // 3️⃣ Al hacer clic, mostrar citas del día
          cell.onclick = () => {
            fetch(`citasPorDia.php?fecha=${fechaStr}`)
              .then(response => response.json())
              .then(data => {
                if (!Array.isArray(data)) {
                  legend.textContent = data.error ? `Error: ${data.error}` : "Respuesta inválida del servidor.";
                  return;
                }

                if (data.length === 0) {
                  legend.textContent = `No hay citas para el ${day}/${month + 1}/${year}.`;
                } else {
                  let contenido = `<strong>Citas para el ${day}/${month + 1}/${year}:</strong><ul>`;
                  data.forEach(cita => {
                    contenido += `<li><strong>${cita.hora}</strong> - ${cita.mascota} (Propietario: ${cita.propietario})</li>`;
                  });
                  contenido += "</ul>";
                  legend.innerHTML = contenido;
                }
              })
              .catch(error => {
                legend.textContent = "Error al cargar las citas.";
                console.error("Error al hacer fetch:", error);
              });
          };

          calendar.appendChild(cell);
        }
      })
      .catch(error => {
        console.error("Error al obtener días con citas:", error);
      });
  }

  // Inicialización
  const now = new Date();
  monthSelect.value = now.getMonth();
  yearSelect.value = now.getFullYear();
  monthSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));
  yearSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));
  renderCalendar(now.getMonth(), now.getFullYear());
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

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Conexión a la base de datos
require __DIR__ . '/../../configuracion/conexion.php';
$conn = conexion();

// Consulta de estadísticas
$stmtUsuarios = $conn->query("SELECT COUNT(*) FROM usuarios");
$totalUsuarios = $stmtUsuarios->fetchColumn();

// Contar todas las citas que NO estén canceladas
$stmtCitas = $conn->query("SELECT COUNT(*) FROM citas WHERE estado != 'Cancelada'");
$totalCitasAgendadas = $stmtCitas->fetchColumn();

// Contar solo las canceladas
$stmtCanceladas = $conn->query("SELECT COUNT(*) FROM citas WHERE estado = 'Cancelada'");
$totalCitasCanceladas = $stmtCanceladas->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agendamiento Administrador</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/vistaAdmin.css">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <style>
    .calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      text-align: center;
      font-weight: bold;
      color: #1b1b4d;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="logo-container">
        <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
    </div>
    <h1>Agenda Administrador</h1>
    <div class="admin-info">
      <p class="nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
    </div>
  </div>

  <div class="panel">
    <div class="panel-izquierda">
      <div class="panel-card">
        <div class="panel-title">Usuarios registrados</div>
        <p style="text-align:center;"><?php echo $totalUsuarios; ?> usuarios</p>
      </div>

      <div class="panel-card">
        <div class="panel-title">Citas agendadas</div>
        <p style="text-align:center;"><?php echo $totalCitasAgendadas; ?> citas</p>
      </div>

      <div class="panel-card">
        <div class="panel-title">Citas canceladas</div>
        <p style="text-align:center;"><?php echo $totalCitasCanceladas; ?> canceladas</p>
      </div>
    </div>

    <div class="panel-derecha">
      <div class="calendar-container">
        <div class="calendar-header">
          <select id="month"></select>
          <select id="year"></select>
        </div>

        <div class="calendar-days">
          <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
        </div>

        <div id="calendar" class="calendar-grid"></div>

        <div class="calendar-legend" id="calendar-legend">Seleccione un día para ver detalles</div>
      </div>
    </div>
  </div>

 <script>
  const calendar = document.getElementById("calendar");
  const monthSelect = document.getElementById("month");
  const yearSelect = document.getElementById("year");
  const legend = document.getElementById("calendar-legend");

  const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

  for (let i = 0; i < months.length; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.text = months[i];
    monthSelect.appendChild(option);
  }

  const currentYear = new Date().getFullYear();
  for (let i = currentYear - 5; i <= currentYear + 5; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.text = i;
    yearSelect.appendChild(option);
  }

  function renderCalendar(month, year) {
    calendar.innerHTML = "";
    const firstDay = new Date(year, month).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    // 1️⃣ Primero traemos las fechas con citas
    fetch(`diasConCitas.php?mes=${month + 1}&anio=${year}`)
      .then(response => response.json())
      .then(fechas => {
        const fechasConCitas = new Set(fechas); // Para buscar rápidamente

        for (let i = 0; i < firstDay; i++) {
          calendar.innerHTML += '<div class="calendar-cell"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const cell = document.createElement("div");
          cell.className = "calendar-cell";

          const fechaStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

          // Marca el día actual
          if (
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
          ) {
            cell.classList.add("today");
          }

          // 2️⃣ Si ese día tiene citas, lo marcamos
          if (fechasConCitas.has(fechaStr)) {
            cell.classList.add("con-cita"); // clase para fondo azul
          }

          cell.textContent = day;

          // 3️⃣ Al hacer clic, mostrar citas del día
          cell.onclick = () => {
            fetch(`citasPorDia.php?fecha=${fechaStr}`)
              .then(response => response.json())
              .then(data => {
                if (!Array.isArray(data)) {
                  legend.textContent = data.error ? `Error: ${data.error}` : "Respuesta inválida del servidor.";
                  return;
                }

                if (data.length === 0) {
                  legend.textContent = `No hay citas para el ${day}/${month + 1}/${year}.`;
                } else {
                  let contenido = `<strong>Citas para el ${day}/${month + 1}/${year}:</strong><ul>`;
                  data.forEach(cita => {
                    contenido += `<li><strong>${cita.hora}</strong> - ${cita.mascota} (Propietario: ${cita.propietario})</li>`;
                  });
                  contenido += "</ul>";
                  legend.innerHTML = contenido;
                }
              })
              .catch(error => {
                legend.textContent = "Error al cargar las citas.";
                console.error("Error al hacer fetch:", error);
              });
          };

          calendar.appendChild(cell);
        }
      })
      .catch(error => {
        console.error("Error al obtener días con citas:", error);
      });
  }

  // Inicialización
  const now = new Date();
  monthSelect.value = now.getMonth();
  yearSelect.value = now.getFullYear();
  monthSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));
  yearSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));
  renderCalendar(now.getMonth(), now.getFullYear());
</script>



</body>
</html>
>>>>>>> f09693777529f8988286f9f474767640441d8127
