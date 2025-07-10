<?php
require_once '../../configuracion/conexion.php';
$pdo = conexion(); // Llama a tu función de conexión para obtener el objeto PDO

// Obtener el ID de la mascota desde la URL
$id_mascota = $_GET['id_mascota'] ?? null;

if (!$id_mascota) {
    die("Error: ID de mascota no proporcionado para ver el perfil.");
}

$pet = null; // Inicializamos $pet para evitar errores si no se encuentra
try {
    $stmt = $pdo->prepare("
        SELECT
            m.id_mascota, m.nombre AS nombre_mascota, m.especie, m.raza, m.sexo, m.edad, m.color,
            m.microchip, m.collar, m.vacunas, m.esterilizacion, m.condiciones_medicas,
            m.alergias, m.medicamentos_preventivos, m.cirugias, m.hospitalizaciones,
            m.comportamiento, m.ejercicio, m.alimento, m.horario_alimentacion, m.premios,
            m.suplementos, m.agua, m.alergias_alimentarias, m.aseo, m.restricciones_ejercicio,
            m.temperamento, m.miedos, m.ubicacion_microchip, m.grupo_sanguineo,
            m.reacciones_anestesia, m.deseos_final_vida,
            m.imagen,
            -- Columnas del dueño (desde la tabla 'usuarios')
            u.nombre AS nombre_dueno, u.apellido AS apellido_dueno, u.direccion AS direccion_dueno,
            u.telefono AS telefono_dueno, u.correo AS correo_dueno,
            -- Columnas de contacto de emergencia, veterinario, clínica y seguro (desde la tabla 'mascotas')
            m.veterinario_habitual AS veterinario_mascota,
            m.clinica_emergencia AS clinica_emergencia_mascota,
            m.seguro_mascotas AS seguro_mascotas_mascota
        FROM
            mascotas m
        JOIN
            usuarios u ON m.id_usuario = u.id_usuario
        WHERE
            m.id_mascota = ?
    ");
    $stmt->execute([$id_mascota]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        die("Error: Mascota con ID " . htmlspecialchars($id_mascota) . " no encontrada.");
    }

} catch (\PDOException $e) {
    die("Error de base de datos al obtener datos de la mascota: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Detallado de Mascota: <?php echo htmlspecialchars($pet['nombre_mascota']); ?></title>
    <link rel="stylesheet" href="../../assets/css/perfil.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <style>
        /* Estilos específicos para la sección de reportes */
        .report-list {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .report-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .report-list th, .report-list td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .report-list th {
            background-color: #e6e6e6;
            color: #333;
            font-weight: bold;
        }
        .report-list td a {
            color: black;
            text-decoration: none;
        }
        .report-list td a:hover {
            text-decoration: none;
        }
        .empty-message {
            text-align: center;
            color: #777;
            padding: 20px;
            font-style: italic;
        }
        /* Añadir un poco de estilo para el botón de PDF si no usas Bootstrap */
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .btn-info {
            color: #fff;
            background-color: #6EACDA;
            border-color: #6EACDA;
        }
        .btn-info:hover {
            background-color:rgba(110, 171, 218, 0.68);
            border-color: rgba(110, 171, 218, 0.68);
        }
        .btn-sm {
            padding: 5px 9px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <img src="../../assets/img/logo negativo.png" alt="Logo de la clínica" class="logo">
            <h1 class="page-title">Perfil de Mascota</h1>
        </div>
    </header>

    <main class="main-content">
        <div class="pet-profile">
            <section class="pet-overview">
                <div class="pet-image">
                   <img id="imagenMascota" src="../../assets/img/mascotas/<?php echo htmlspecialchars($pet['imagen'] ?? 'default-pet.png'); ?>" alt="Foto de la Mascota">

                </div>
                <div class="pet-details-brief">
                    <h2 id="nombreMascota" class="pet-name"><?php echo htmlspecialchars($pet['nombre_mascota'] ?? ''); ?></h2>
                    <p class="pet-breed"><strong class="label">Raza:</strong> <span id="razaMascota"><?php echo htmlspecialchars($pet['raza'] ?? ''); ?></span></p>
                    <p class="pet-owner"><strong class="label">Dueño:</strong> <span id="nombreDueño"><?php echo htmlspecialchars($pet['nombre_dueno'] . ' ' . $pet['apellido_dueno'] ?? ''); ?></span></p>
                    <p class="pet-gender"><strong class="label">Género:</strong> <span id="sexoMascota"><?php echo htmlspecialchars($pet['sexo'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="pet-identification">
                <h3 class="section-title">Identificación</h3>
                <div class="info-grid">
                    <p><strong class="label">Especie:</strong> <span id="especieMascota"><?php echo htmlspecialchars($pet['especie'] ?? ''); ?></span></p>
                    <p><strong class="label">Edad:</strong> <span id="edadMascota"><?php echo htmlspecialchars($pet['edad'] ?? ''); ?></span></p>
                    <p><strong class="label">Color y Marcas:</strong> <span id="colorMascota"><?php echo htmlspecialchars($pet['color'] ?? ''); ?></span></p>
                    <p><strong class="label">Microchip:</strong> <span id="microchipMascota"><?php echo htmlspecialchars($pet['microchip'] ?? ''); ?></span></p>
                    <p><strong class="label">Collar/Placa:</strong> <span id="collarMascota"><?php echo htmlspecialchars($pet['collar'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="owner-contact">
                <h3 class="section-title">Contacto del Dueño</h3>
                <div class="info-grid">
                    <p><strong class="label">Nombre(s):</strong> <span id="nombreDueñoContacto"><?php echo htmlspecialchars($pet['nombre_dueno'] . ' ' . $pet['apellido_dueno'] ?? ''); ?></span></p>
                    <p><strong class="label">Dirección:</strong> <span id="direccionDueño"><?php echo htmlspecialchars($pet['direccion_dueno'] ?? ''); ?></span></p>
                    <p><strong class="label">Teléfonos:</strong> <span id="telefonoDueño"><?php echo htmlspecialchars($pet['telefono_dueno'] ?? ''); ?></span></p>
                    <p><strong class="label">Email:</strong> <span id="emailDueño"><?php echo htmlspecialchars($pet['correo_dueno'] ?? ''); ?></span></p>
                    <p><strong class="label">Veterinario:</strong> <span id="veterinario"><?php echo htmlspecialchars($pet['veterinario_mascota'] ?? ''); ?></span></p>
                    <p><strong class="label">Clínica Emergencia:</strong> <span id="clinicaEmergencia"><?php echo htmlspecialchars($pet['clinica_emergencia_mascota'] ?? ''); ?></span></p>
                    <p><strong class="label">Seguro:</strong> <span id="seguroMascotas"><?php echo htmlspecialchars($pet['seguro_mascotas_mascota'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="medical-history">
                <h3 class="section-title">Historial Médico</h3>
                <div class="info-grid">
                    <p><strong class="label">Vacunas:</strong> <span id="vacunasMascota"><?php echo htmlspecialchars($pet['vacunas'] ?? ''); ?></span></p>
                    <p><strong class="label">Esterilización/Castración:</strong> <span id="esterilizacionMascota"><?php echo htmlspecialchars($pet['esterilizacion'] ?? ''); ?></span></p>
                    <p><strong class="label">Condiciones Médicas:</strong> <span id="condiciones_medicas"><?php echo htmlspecialchars($pet['condiciones_medicas'] ?? ''); ?></span></p>
                    <p><strong class="label">Alergias:</strong> <span id="alergiasMascota"><?php echo htmlspecialchars($pet['alergias'] ?? ''); ?></span></p>
                    <p><strong class="label">Medicamentos:</strong> <span id="medicamentosMascota"><?php echo htmlspecialchars($pet['medicamentos_preventivos'] ?? ''); ?></span></p>
                    <p><strong class="label">Preventivos:</strong> <span id="medicamentosPreventivos"><?php echo htmlspecialchars($pet['medicamentos_preventivos'] ?? ''); ?></span></p>
                    <p><strong class="label">Cirugías:</strong> <span id="cirugiasMascota"><?php echo htmlspecialchars($pet['cirugias'] ?? ''); ?></span></p>
                    <p><strong class="label">Hospitalizaciones:</strong> <span id="hospitalizacionesMascota"><?php echo htmlspecialchars($pet['hospitalizaciones'] ?? ''); ?></span></p>
                    <p><strong class="label">Comportamiento:</strong> <span id="comportamientoMascota"><?php echo htmlspecialchars($pet['comportamiento'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="dietary-info">
                <h3 class="section-title">Información Dietética</h3>
                <div class="info-grid">
                    <p><strong class="label">Alimento:</strong> <span id="alimentoMascota"><?php echo htmlspecialchars($pet['alimento'] ?? ''); ?></span></p>
                    <p><strong class="label">Horario:</strong> <span id="horarioAlimentacion"><?php echo htmlspecialchars($pet['horario_alimentacion'] ?? ''); ?></span></p>
                    <p><strong class="label">Premios:</strong> <span id="premiosMascota"><?php echo htmlspecialchars($pet['premios'] ?? ''); ?></span></p>
                    <p><strong class="label">Suplementos:</strong> <span id="suplementosMascota"><?php echo htmlspecialchars($pet['suplementos'] ?? ''); ?></span></p>
                    <p><strong class="label">Agua:</strong> <span id="aguaMascota"><?php echo htmlspecialchars($pet['agua'] ?? ''); ?></span></p>
                    <p><strong class="label">Alergias Alimentarias:</strong> <span id="alergiasAlimentarias"><?php echo htmlspecialchars($pet['alergias_alimentarias'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="other-info">
                <h3 class="section-title">Otra Información</h3>
                <div class="info-grid">
                    <p><strong class="label">Aseo:</strong> <span id="aseoMascota"><?php echo htmlspecialchars($pet['aseo'] ?? ''); ?></span></p>
                    <p><strong class="label">Restricciones Ejercicio:</strong> <span id="restriccionesEjercicio"><?php echo htmlspecialchars($pet['restricciones_ejercicio'] ?? ''); ?></span></p>
                    <p><strong class="label">Temperamento:</strong> <span id="temperamentoMascota"><?php echo htmlspecialchars($pet['temperamento'] ?? ''); ?></span></p>
                    <p><strong class="label">Miedos/Fobias:</strong> <span id="miedosMascota"><?php echo htmlspecialchars($pet['miedos'] ?? ''); ?></span></p>
                    <p><strong class="label">Ubicación Microchip:</strong> <span id="ubicacionMicrochip"><?php echo htmlspecialchars($pet['ubicacion_microchip'] ?? ''); ?></span></p>
                    <p><strong class="label">Grupo Sanguíneo:</strong> <span id="grupoSanguineoMascota"><?php echo htmlspecialchars($pet['grupo_sanguineo'] ?? ''); ?></span></p>
                    <p><strong class="label">Reacciones Anestesia:</strong> <span id="reaccionesAnestesia"><?php echo htmlspecialchars($pet['reacciones_anestesia'] ?? ''); ?></span></p>
                    <p><strong class="label">Deseos Final Vida:</strong> <span id="deseosFinalVida"><?php echo htmlspecialchars($pet['deseos_final_vida'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="report-list">
                <h3 class="section-title">Historial de Reportes Médicos del Veterinario</h3>
                <div id="reportsContainer">
                    <p class="empty-message">Cargando reportes...</p>
                </div>
            </section>

            <div class="navigation">
                <a href="javascript:history.back()" class="back-link">← Volver</a>
            </div>
        </div>
    </main>

    <script>
        async function loadPetReports(petId) {
            const reportsContainer = document.getElementById('reportsContainer');
            reportsContainer.innerHTML = '<p class="empty-message">Cargando reportes...</p>';

            try {
                const response = await fetch(`../gestionMascotas/get_pet_reports.php?id_mascota=${petId}`);

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
                }

                const data = await response.json();

                if (data.success && data.reports.length > 0) {
                    let tableHtml = '<table><thead><tr><th>Fecha</th><th>Diagnóstico</th><th>Acciones</th></tr></thead><tbody>';
                    data.reports.forEach(report => {
                        const displayDiagnostico = report.diagnostico ? report.diagnostico.substring(0, 100) + (report.diagnostico.length > 100 ? '...' : '') : 'N/A';
                        tableHtml += `
                            <tr>
                                <td>${report.fecha_reporte}</td>
                                <td>${displayDiagnostico}</td>
                                <td>
                                    <a href="generar_pdf.php?id_reporte=${report.id_reporte}" target="_blank" class="btn btn-info btn-sm">Ver PDF</a>
                                </td>
                            </tr>
                        `;
                    });
                    tableHtml += '</tbody></table>';
                    reportsContainer.innerHTML = tableHtml;
                } else if (data.success && data.reports.length === 0) {
                    reportsContainer.innerHTML = '<p class="empty-message">No hay reportes médicos registrados para esta mascota.</p>';
                } else {
                    reportsContainer.innerHTML = `<p class="empty-message">Error al cargar los reportes: ${data.error || 'Mensaje de error desconocido'}</p>`;
                    console.error('Error en API:', data.error);
                }

            } catch (error) {
                reportsContainer.innerHTML = '<p class="empty-message">Error de conexión al cargar los reportes.</p>';
                console.error('Error de conexión (fetch):', error);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const petId = new URLSearchParams(window.location.search).get('id_mascota');
            if (petId) {
                loadPetReports(petId);
            } else {
                document.getElementById('reportsContainer').innerHTML = '<p class="empty-message">ID de mascota no especificado en la URL.</p>';
            }
        });
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