<?php
  session_start();

  if (!isset($_SESSION['usuario'])) {
      header("Location: ../login/login.php");
      exit;
  }

  $usuario = $_SESSION['usuario'];
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
      <p style="text-align:center;">325 usuarios</p>
    </div>

    <div class="panel-card">
      <div class="panel-title">Citas agendadas</div>
      <p style="text-align:center;">12 citas</p>
    </div>

    <div class="panel-card">
      <div class="panel-title">Citas canceladas</div>
      <p style="text-align:center;">3 canceladas</p>
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

      for (let i = 0; i < firstDay; i++) {
        calendar.innerHTML += '<div class="calendar-cell"></div>';
      }

      const today = new Date();

      for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement("div");
        cell.className = "calendar-cell";
        if (
          day === today.getDate() &&
          month === today.getMonth() &&
          year === today.getFullYear()
        ) {
          cell.classList.add("today");
        }
        cell.textContent = day;
        cell.onclick = () => legend.textContent = `Citas para el ${day}/${month + 1}/${year}: Ninguna registrada.`;
        calendar.appendChild(cell);
      }
    }

    monthSelect.value = new Date().getMonth();
    yearSelect.value = new Date().getFullYear();

    monthSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));
    yearSelect.onchange = () => renderCalendar(parseInt(monthSelect.value), parseInt(yearSelect.value));

    renderCalendar(new Date().getMonth(), new Date().getFullYear());
  </script>
  

</body>
</html>