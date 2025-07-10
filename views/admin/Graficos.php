<<<<<<< HEAD
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Gráficos de Administración Veterinaria</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/graficos.css" />
</head>
<body>
  <div class="app-header">
    <div class="logo">
      <img src="../../assets/img/logo negativo.png" alt="Logo de PetLittle" />
    </div>
    <button class="back-button" onclick="history.back()">Volver</button>
  </div>

  <div class="dashboard-container">
    <h1>Panel de Administración</h1>
    <div class="dashboard-grid">
      <div class="dashboard-item" id="citas-mes">
        <h2>Número de Citas por Mes</h2>
        <canvas id="citasPorMesChart"></canvas>
      </div>
      <div class="dashboard-item" id="estado-citas">
        <h2>Estado de las Citas</h2>
        <canvas id="estadoCitasChart"></canvas>
      </div>
      <div class="dashboard-item dashboard-item-full" id="tipos-citas">
        <h2>Especialidades</h2>
        <canvas id="tiposCitasChart"></canvas>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    let chartCitasMes, chartEstadoCitas, chartTiposCitas;

    document.addEventListener("DOMContentLoaded", () => {
      renderGraficos();
    });

    window.addEventListener("resize", () => {
      renderGraficos();
    });

    async function renderGraficos() {
      await cargarCitasPorMes();
      await cargarEstadoCitas();
      await cargarTiposCitas();
    }

    async function cargarCitasPorMes() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=citasPorMes");
        const data = await res.json();
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const etiquetas = data.map(d => meses[d.mes - 1]);
        const valores = data.map(d => parseInt(d.total));

        if (chartCitasMes) chartCitasMes.destroy();

        chartCitasMes = new Chart(document.getElementById('citasPorMesChart'), {
          type: 'bar',
          data: {
            labels: etiquetas,
            datasets: [{
              label: 'Número de Citas',
              data: valores,
              backgroundColor: '#64B5F6',
              borderColor: '#64B5F6',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      } catch (error) {
        console.error("Error al cargar citas por mes:", error);
      }
    }

    async function cargarEstadoCitas() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=estadoCitas");
        const data = await res.json();
        const etiquetas = data.map(d => d.estado);
        const valores = data.map(d => parseInt(d.total));

        if (chartEstadoCitas) chartEstadoCitas.destroy();

        chartEstadoCitas = new Chart(document.getElementById('estadoCitasChart'), {
          type: 'pie',
          data: {
            labels: etiquetas,
            datasets: [{
              data: valores,
              backgroundColor: ['#4FC3F7', '#FFF176', '#F44336'],
              hoverOffset: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      } catch (error) {
        console.error("Error al cargar estados de citas:", error);
      }
    }

    async function cargarTiposCitas() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=tiposCitas");
        const data = await res.json();
        const etiquetas = data.map(d => d.especialidad);
        const valores = data.map(d => parseInt(d.total));

        if (chartTiposCitas) chartTiposCitas.destroy();

        chartTiposCitas = new Chart(document.getElementById('tiposCitasChart'), {
          type: 'bar',
          data: {
            labels: etiquetas,
            datasets: [{
              label: 'Número de Citas',
              data: valores,
              backgroundColor: [
                '#A5D6A7', '#FFD54F', '#EF9A9A',
                '#9FA8DA', '#CE93D8', '#81D4FA',
                '#FFAB91', '#B39DDB', '#80CBC4'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
              x: { beginAtZero: true }
            }
          }
        });
      } catch (error) {
        console.error("Error al cargar tipos de citas:", error);
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

    window.onload = reiniciarTemporizador;
    document.onmousemove = reiniciarTemporizador;
    document.onkeydown = reiniciarTemporizador;
    document.onclick = reiniciarTemporizador;
  </script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>Gráficos de Administración Veterinaria</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/graficos.css" />
</head>
<body>
  <div class="app-header">
    <div class="logo">
      <img src="../../assets/img/logo negativo.png" alt="Logo de PetLittle" />
    </div>
    <button class="back-button" onclick="history.back()">Volver</button>
  </div>

  <div class="dashboard-container">
    <h1>Panel de Administración</h1>
    <div class="dashboard-grid">
      <div class="dashboard-item" id="citas-mes">
        <h2>Número de Citas por Mes</h2>
        <canvas id="citasPorMesChart"></canvas>
      </div>
      <div class="dashboard-item" id="estado-citas">
        <h2>Estado de las Citas</h2>
        <canvas id="estadoCitasChart"></canvas>
      </div>
      <div class="dashboard-item dashboard-item-full" id="tipos-citas">
        <h2>Especialidades</h2>
        <canvas id="tiposCitasChart"></canvas>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    let chartCitasMes, chartEstadoCitas, chartTiposCitas;

    document.addEventListener("DOMContentLoaded", () => {
      renderGraficos();
    });

    window.addEventListener("resize", () => {
      renderGraficos();
    });

    async function renderGraficos() {
      await cargarCitasPorMes();
      await cargarEstadoCitas();
      await cargarTiposCitas();
    }

    async function cargarCitasPorMes() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=citasPorMes");
        const data = await res.json();
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const etiquetas = data.map(d => meses[d.mes - 1]);
        const valores = data.map(d => parseInt(d.total));

        if (chartCitasMes) chartCitasMes.destroy();

        chartCitasMes = new Chart(document.getElementById('citasPorMesChart'), {
          type: 'bar',
          data: {
            labels: etiquetas,
            datasets: [{
              label: 'Número de Citas',
              data: valores,
              backgroundColor: '#64B5F6',
              borderColor: '#64B5F6',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      } catch (error) {
        console.error("Error al cargar citas por mes:", error);
      }
    }

    async function cargarEstadoCitas() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=estadoCitas");
        const data = await res.json();
        const etiquetas = data.map(d => d.estado);
        const valores = data.map(d => parseInt(d.total));

        if (chartEstadoCitas) chartEstadoCitas.destroy();

        chartEstadoCitas = new Chart(document.getElementById('estadoCitasChart'), {
          type: 'pie',
          data: {
            labels: etiquetas,
            datasets: [{
              data: valores,
              backgroundColor: ['#4FC3F7', '#FFF176', '#F44336'],
              hoverOffset: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      } catch (error) {
        console.error("Error al cargar estados de citas:", error);
      }
    }

    async function cargarTiposCitas() {
      try {
        const res = await fetch("../../controllers/graficosController.php?action=tiposCitas");
        const data = await res.json();
        const etiquetas = data.map(d => d.especialidad);
        const valores = data.map(d => parseInt(d.total));

        if (chartTiposCitas) chartTiposCitas.destroy();

        chartTiposCitas = new Chart(document.getElementById('tiposCitasChart'), {
          type: 'bar',
          data: {
            labels: etiquetas,
            datasets: [{
              label: 'Número de Citas',
              data: valores,
              backgroundColor: [
                '#A5D6A7', '#FFD54F', '#EF9A9A',
                '#9FA8DA', '#CE93D8', '#81D4FA',
                '#FFAB91', '#B39DDB', '#80CBC4'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
              x: { beginAtZero: true }
            }
          }
        });
      } catch (error) {
        console.error("Error al cargar tipos de citas:", error);
      }
    }
  </script>
</body>
</html>
>>>>>>> f09693777529f8988286f9f474767640441d8127
