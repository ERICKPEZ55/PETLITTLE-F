<?php

require_once __DIR__ . '/dompdf-3.1.0/dompdf/autoload.inc.php';
require_once '../../configuracion/conexion.php'; // Ajusta esta ruta si es necesario

use Dompdf\Dompdf;
use Dompdf\Options; // Importa Options para poder configurarlas

$pdo = conexion();


$id_reporte = $_GET['id_reporte'] ?? null;

if (!$id_reporte) {
    die("Error: ID de reporte no proporcionado para generar el PDF.");
}

$petAndReportData = null; 

try {
    
    $stmt = $pdo->prepare("
        SELECT
            m.nombre AS nombre_mascota, m.especie, m.raza, m.sexo, m.edad, m.color,
            m.microchip, m.collar, m.vacunas, m.esterilizacion, m.condiciones_medicas AS condicionesMedicas,
            m.alergias, m.medicamentos_preventivos AS medicamentosPreventivos, m.cirugias, m.hospitalizaciones,
            m.comportamiento, m.alimento, m.horario_alimentacion AS horarioAlimentacion, m.premios,
            m.suplementos, m.agua, m.alergias_alimentarias AS alergiasAlimentarias, m.aseo, m.restricciones_ejercicio AS restriccionesEjercicio,
            m.temperamento, m.miedos, m.ubicacion_microchip AS ubicacionMicrochip, m.grupo_sanguineo AS grupoSanguineo,
            m.reacciones_anestesia AS reaccionesAnestesia, m.deseos_final_vida AS deseosFinalVida,
            m.imagen,
            u.nombre AS nombre_dueno, u.apellido AS apellido_dueno, u.direccion AS direccionDueño,
            u.telefono AS telefonoDueño, u.correo AS emailDueño,
            m.veterinario_habitual AS veterinario, m.clinica_emergencia AS clinicaEmergencia, m.seguro_mascotas AS seguroMascotas,
            -- Datos específicos del reporte
            r.fecha_reporte, r.diagnostico, r.sintomas, r.tratamiento, r.medicamentos_recetados, r.recomendaciones, r.archivo_adjunto_ruta
        FROM
            mascotas m
        JOIN
            usuarios u ON m.id_usuario = u.id_usuario
        JOIN
            reportes_medicos r ON m.id_mascota = r.id_mascota
        WHERE
            r.id_reporte = ?
    ");
    $stmt->execute([$id_reporte]);
    $petAndReportData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$petAndReportData) {
        die("Error: No se encontró el reporte médico o la mascota asociada con el ID " . htmlspecialchars($id_reporte) . ".");
    }

    // Adaptar los nombres de columna de la DB a los esperados por la función 'seccion' y HTML
    $pet = $petAndReportData;
    $pet['nombre'] = $pet['nombre_mascota']; // Para el título del PDF

    // Combina nombre y apellido del dueño
    $pet['nombreDueñoContacto'] = ($pet['nombre_dueno'] ?? '') . ' ' . ($pet['apellido_dueno'] ?? '');
    // Asigna un valor vacío para contactoEmergencia si no tienes esa columna en la DB
    $pet['contactoEmergencia'] = ''; 

} catch (\PDOException $e) {
    die("Error de base de datos al obtener datos para el PDF: " . $e->getMessage());
}

// --- Inicio de la generación del PDF ---
ob_start(); // Inicia el almacenamiento en búfer de la salida HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Médico de Mascota - <?php echo htmlspecialchars($pet['nombre_mascota'] ?? 'Desconocida'); ?></title>
    <style>
        /* Estilos CSS para el PDF */
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .header h2 { font-size: 18px; color: #1c2561; margin: 0; }
        .header h3 { font-size: 24px; color: #333; margin: 5px 0 15px; }
        
        .section-title {
            background-color: #1c2561;
            color: white;
            padding: 8px 10px;
            font-size: 14px;
            margin-top: 25px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        /* Estilos para la nueva estructura de tabla */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid; /* Evita que las secciones se corten entre páginas */
        }
        .info-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
            line-height: 1.4;
        }
        .info-table td:first-child {
            width: 50%; /* Controla el ancho de la primera columna en cada fila */
        }
        .info-table td:last-child {
            width: 50%; /* Controla el ancho de la segunda columna en cada fila */
        }
        .label { font-weight: bold; color: #555; }
        
        /* Estilos específicos para el reporte médico */
        .report-details .info-table td {
            background-color: #f9f9f9;
            border-radius: 5px; /* Esto no aplicará en celdas de tabla */
        }
        .report-details .label {
            color: #1c2561;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Perfil Médico de Mascota</h2>
        <h3><?php echo htmlspecialchars($pet['nombre_mascota'] ?? 'Nombre no disponible'); ?></h3>
    </div>

    <?php
    // Función para generar secciones de información usando TABLAS para mejor compatibilidad con Dompdf
    function seccion($titulo, $campos, $pet, $fullWidthFields = []) {
        echo "<div class='section-title'>" . htmlspecialchars($titulo) . "</div>";
        echo "<table class='info-table'>"; // Usamos tabla en lugar de div.info-grid
        $i = 0;
        foreach ($campos as $campo => $label) {
            $valor = htmlspecialchars($pet[$campo] ?? 'N/A');
            
            // Si es un campo de ancho completo, cierra la fila actual y abre una nueva
            if (in_array($campo, $fullWidthFields)) {
                if ($i % 2 != 0) { // Si la fila anterior no se cerró (era impar), la cerramos
                    echo "<td></td></tr>"; // Celda vacía para completar la fila si es necesario
                }
                echo "<tr><td colspan='2'><span class='label'>" . htmlspecialchars($label) . ":</span> $valor</td></tr>";
                $i = 0; // Reinicia el contador para la próxima fila
            } else {
                if ($i % 2 == 0) { // Si es un índice par, abrimos una nueva fila
                    echo "<tr>";
                }
                echo "<td><span class='label'>" . htmlspecialchars($label) . ":</span> $valor</td>";
                if ($i % 2 != 0) { // Si es un índice impar, cerramos la fila
                    echo "</tr>";
                }
                $i++;
            }
        }
        if ($i % 2 != 0) { // Asegura que la última fila se cierre si era impar
            echo "<td></td></tr>";
        }
        echo "</table>";
    }

    // Datos para las secciones del PDF (ahora se renderizarán en tablas)
    seccion('Identificación', [
        'especie' => 'Especie',
        'raza' => 'Raza',
        'sexo' => 'Género',
        'edad' => 'Edad',
        'color' => 'Color y Marcas',
        'microchip' => 'Microchip',
        'ubicacionMicrochip' => 'Ubicación Microchip',
        'collar' => 'Collar/Placa',
        'grupoSanguineo' => 'Grupo Sanguíneo'
    ], $pet);

    seccion('Contacto del Dueño y Veterinario', [
        'nombreDueñoContacto' => 'Nombre del Dueño',
        'direccionDueño' => 'Dirección del Dueño',
        'telefonoDueño' => 'Teléfono del Dueño',
        'emailDueño' => 'Email del Dueño',
        'contactoEmergencia' => 'Contacto de Emergencia',
        'veterinario' => 'Veterinario Habitual',
        'clinicaEmergencia' => 'Clínica de Emergencia',
        'seguroMascotas' => 'Seguro de Mascotas'
    ], $pet);

    // Sección para el Reporte Médico Específico (ahora también usando una tabla)
    echo "<div class='section-title'>Detalles del Reporte Médico</div>";
    echo "<table class='info-table report-details'>";
    echo "<tr>";
    echo "<td><span class='label'>Fecha del Reporte:</span> " . htmlspecialchars($pet['fecha_reporte'] ?? 'N/A') . "</td>";
    echo "<td><span class='label'>ID del Reporte:</span> " . htmlspecialchars($id_reporte) . "</td>";
    echo "</tr>";
    echo "<tr><td colspan='2'><span class='label'>Síntomas:</span> " . htmlspecialchars($pet['sintomas'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td colspan='2'><span class='label'>Diagnóstico:</span> " . htmlspecialchars($pet['diagnostico'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td colspan='2'><span class='label'>Tratamiento:</span> " . htmlspecialchars($pet['tratamiento'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td colspan='2'><span class='label'>Medicamentos Recetados:</span> " . htmlspecialchars($pet['medicamentos_recetados'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td colspan='2'><span class='label'>Recomendaciones:</span> " . htmlspecialchars($pet['recomendaciones'] ?? 'N/A') . "</td></tr>";
    echo "</table>";


    seccion('Historial Médico General', [
        'vacunas' => 'Vacunas',
        'esterilizacion' => 'Esterilización/Castración',
        'condicionesMedicas' => 'Condiciones Médicas',
        'alergias' => 'Alergias',
        'medicamentosPreventivos' => 'Medicamentos Preventivos',
        'cirugias' => 'Cirugías',
        'hospitalizaciones' => 'Hospitalizaciones',
        'reaccionesAnestesia' => 'Reacciones a Anestesia'
    ], $pet);

    seccion('Comportamiento y Ejercicio', [
        'comportamiento' => 'Comportamiento',
        'temperamento' => 'Temperamento',
        'miedos' => 'Miedos/Fobias',
        'ejercicio' => 'Ejercicio',
        'restriccionesEjercicio' => 'Restricciones de Ejercicio'
    ], $pet);

    seccion('Información Dietética', [
        'alimento' => 'Alimento',
        'horarioAlimentacion' => 'Horario de Alimentación',
        'premios' => 'Premios',
        'suplementos' => 'Suplementos',
        'agua' => 'Consumo de Agua',
        'alergiasAlimentarias' => 'Alergias Alimentarias'
    ], $pet);

    seccion('Aseo y Notas Adicionales', [
        'aseo' => 'Aseo',
        'deseosFinalVida' => 'Deseos al Final de la Vida'
    ], $pet, ['deseosFinalVida']);
    ?>
</body>
</html>
<?php
$html = ob_get_clean(); // Obtiene el contenido del búfer y lo limpia

$dompdf = new Dompdf();

// Configurar opciones de Dompdf para deshabilitar carga remota
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false); // ¡IMPORTANTE! Deshabilitar la carga de recursos remotos
$dompdf->setOptions($options); // Aplicar las opciones al objeto Dompdf

$dompdf->loadHtml($html);

// Ajusta el tamaño del papel y la orientación (A4, portrait es lo más común)
$dompdf->setPaper('A4', 'portrait');

// Renderiza el HTML a PDF
$dompdf->render();

// Envía el PDF al navegador para descarga
$nombreArchivo = "reporte_medico_" . ($pet['nombre_mascota'] ?? 'mascota') . "_" . ($pet['fecha_reporte'] ?? date('Y-m-d')) . ".pdf";
$dompdf->stream($nombreArchivo, ["Attachment" => true]); // "Attachment => true" fuerza la descarga