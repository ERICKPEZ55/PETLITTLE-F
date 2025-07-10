<?php
session_start();
require_once '../../configuracion/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../models/login.php");
    exit;
}

$pdo = conexion();
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT o.id_orden, o.fecha_creacion, o.pruebas, o.laboratorio_destino,
               m.nombre AS nombre_mascota,
               u.nombre AS nombre_usuario, u.apellido
        FROM ordenes_laboratorio o
        JOIN mascotas m ON o.id_mascota = m.id_mascota
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE u.id_usuario = :id_usuario
        ORDER BY o.fecha_creacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios Clínicos</title>
    <link rel="stylesheet" href="../../assets/css/laboratorios.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../assets/img/logo negativo.png" alt="logo" class="logo-img">
        </div>
        <h2>Laboratorios clínicos</h2>
        <a href="../usuario/agendamiento.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <aside>
        <ul>
            <li><a href="agendamientoCalen.php">Agendar Cita</a></li>
            <li><a href="../gestionCitas/tablasCitas.php">Citas Agendadas</a></li>
            <li><a href="laboratorios.php">Laboratorio Clínico</a></li>
        </ul>
    </aside>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Propietario</th>
                    <th>Pruebas</th>
                    <th>Laboratorio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?= htmlspecialchars($orden['fecha_creacion']) ?></td>
                    <td><?= htmlspecialchars($orden['nombre_mascota']) ?></td>
                    <td><?= htmlspecialchars($orden['nombre_usuario'] . ' ' . $orden['apellido']) ?></td>
                    <td><?= htmlspecialchars($orden['pruebas']) ?></td>
                    <td><?= htmlspecialchars($orden['laboratorio_destino']) ?></td>
                    <td><span class="estado-pendiente">Pendiente</span></td>
                    <td><button class="btn-ver" data-id="<?= $orden['id_orden'] ?>">Ver Orden</button></td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
       <!-- Overlay y Modal para mostrar detalle -->
    <div id="overlay" class="overlay" onclick="cerrarDetalle()"></div>
    <div id="detalleModal" class="ver-detalle">
        <h2>Orden laboratorio</h2>
        <div id="detalleContenido">
            <!-- Aquí se inserta dinámicamente el contenido de la orden -->
        </div>
        <button class="btn-cerrar" onclick="cerrarDetalle()">Cerrar</button>
    </div>


    <script>
            document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('overlay');
            const modal = document.getElementById('detalleModal');
            const contenido = document.getElementById('detalleContenido');

            document.querySelectorAll('.btn-ver').forEach(boton => {
                boton.addEventListener('click', () => {
                    const id = boton.getAttribute('data-id');
                    console.log("ID enviado al fetch:", id);

                    fetch(`../../controllers/getOrdenDetalle.php?id=${id}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                contenido.innerHTML = `<p>${data.error}</p>`;
                            } else {
                                contenido.innerHTML = `
                                    <p><strong>Paciente:</strong> ${data.mascota} (${data.raza})</p>
                                    <p><strong>Propietario:</strong> ${data.propietario}</p>
                                    <p><strong>Tipo de muestra:</strong> ${data.tipo_muestra}</p>
                                    <p><strong>Pruebas:</strong> ${data.pruebas}</p>
                                    <p><strong>Laboratorio destino:</strong> ${data.laboratorio_destino}</p>
                                    <p><strong>Urgencia:</strong> ${data.urgencia}</p>
                                    <p><strong>Notas clínicas:</strong> ${data.notas}</p>
                                    <p><strong>Fecha de creación:</strong> ${data.fecha_creacion}</p>
                                `;
                            }

                            overlay.style.display = 'block';
                            modal.style.display = 'block';
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            contenido.innerHTML = `<p>Error al obtener la orden</p>`;
                            overlay.style.display = 'block';
                            modal.style.display = 'block';
                        });


                });
            });
        });

        function cerrarDetalle() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('detalleModal').style.display = 'none';
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
