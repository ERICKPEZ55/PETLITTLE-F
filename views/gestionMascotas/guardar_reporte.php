<?php

// guardar_reporte.php
// Este script guarda los datos de un nuevo reporte médico y actualiza la información de la mascota.

// Habilitar reporte de errores para depuración (eliminar o comentar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ajusta la ruta a tu archivo de configuración de base de datos
// Debería ser: ../../configuracion/conexion.php
require_once '../../configuracion/conexion.php';
$pdo = conexion(); // Llama a tu función de conexión para obtener el objeto PDO

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es POST, redirigir a una página principal o de error
    header('Location: ../../index.php'); 
    exit();
}

try {
    // Iniciar una transacción para asegurar que ambas operaciones (INSERT y UPDATE)
    // se completen o ninguna lo haga.
    $pdo->beginTransaction();

    // 1. Recibir los datos del formulario para el reporte médico
    $id_mascota              = $_POST['id_mascota'] ?? null;
    $fecha_reporte           = $_POST['fechaReporte'] ?? null;
    $sintomas                = $_POST['sintomas'] ?? '';
    $diagnostico             = $_POST['diagnostico'] ?? '';
    $tratamiento             = $_POST['tratamiento'] ?? '';
    $medicamentos_recetados  = $_POST['medicamentos'] ?? '';
    $recomendaciones         = $_POST['recomendaciones'] ?? '';

    // Validar datos mínimos para el reporte médico
    if (!$id_mascota || !$fecha_reporte || !$sintomas || !$diagnostico || !$tratamiento) {
        // Rollback de la transacción antes de salir
        $pdo->rollBack();
        echo "<script>alert('Error: Faltan datos esenciales para guardar el reporte.'); window.history.back();</script>";
        exit();
    }

    // Inicializa la ruta del archivo adjunto como null
    $archivo_adjunto_ruta = null;

    // Lógica para manejar la subida de un archivo adjunto
    if (isset($_FILES['archivoAdjunto']) && $_FILES['archivoAdjunto']['error'] === UPLOAD_ERR_OK) {
        // La ruta de subida debe ser accesible desde la raíz del proyecto web
        // Si este script está en 'PetLittle/views/gestionMascotas/', y 'uploads/' está en 'PetLittle/'
        $uploadDir = '../../uploads/reportes/'; 
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Crea el directorio si no existe (0755 para permisos seguros)
        }

        $fileName = basename($_FILES['archivoAdjunto']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        // Asegúrate de que el nombre de archivo sea seguro y único
        $uniqueFileName = uniqid('reporte_') . '.' . $fileExtension; 
        $filePath = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($_FILES['archivoAdjunto']['tmp_name'], $filePath)) {
            // Guarda la ruta relativa a la raíz del proyecto para la DB
            // Ejemplo: 'uploads/reportes/nombre_unico.pdf'
            $archivo_adjunto_ruta = 'uploads/reportes/' . $uniqueFileName; 
        } else {
            error_log("Error al mover el archivo subido: " . $_FILES['archivoAdjunto']['name'] . " - Error Code: " . $_FILES['archivoAdjunto']['error']);
            // Puedes decidir si el error de archivo es crítico o si el reporte se guarda sin él
            // Para este ejemplo, si falla la subida, archivo_adjunto_ruta seguirá siendo null
        }
    }

    // Insertar los datos en la tabla `reportes_medicos`
    $stmt_insert_reporte = $pdo->prepare("
        INSERT INTO `reportes_medicos` (
            `id_mascota`, `fecha_reporte`, `sintomas`, `diagnostico`,
            `tratamiento`, `medicamentos_recetados`, `recomendaciones`, `archivo_adjunto_ruta`
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt_insert_reporte->execute([
        $id_mascota,
        $fecha_reporte,
        $sintomas,
        $diagnostico,
        $tratamiento,
        $medicamentos_recetados,
        $recomendaciones,
        $archivo_adjunto_ruta
    ]);

    // 2. Recibir y actualizar los datos de la mascota en la tabla `mascotas`
    // Estos son los campos que se llenan automáticamente desde 'get_pet_data.php'
    // y se envían de vuelta con el formulario.
    // Solo se actualizarán si se envían.

    $raza                  = $_POST['raza'] ?? null;
    $sexo                  = $_POST['sexo'] ?? null;
    $especie               = $_POST['especie'] ?? null;
    $edad                  = $_POST['edad'] ?? null;
    $color                 = $_POST['color'] ?? null;
    $microchip             = $_POST['microchip'] ?? null;
    $collar                = $_POST['collar'] ?? null;
    
    // Asumiendo que estos campos de contacto/referencia del dueño están en la tabla `mascotas`
    // Si 'contactoEmergencia' es un campo real, asegúrate de cómo lo manejas en tu DB
    $veterinario_habitual  = $_POST['veterinario'] ?? null;
    $clinica_emergencia    = $_POST['clinicaEmergencia'] ?? null;
    $seguro_mascotas       = $_POST['seguroMascotas'] ?? null;

    $vacunas               = $_POST['vacunas'] ?? null;
    $esterilizacion        = $_POST['esterilizacion'] ?? null;
    $condiciones_medicas   = $_POST['condicionesMedicas'] ?? null;
    $alergias              = $_POST['alergias'] ?? null;
    $medicamentos_preventivos = $_POST['medicamentosPreventivos'] ?? null;
    $cirugias              = $_POST['cirugias'] ?? null;
    $hospitalizaciones     = $_POST['hospitalizaciones'] ?? null;
    $comportamiento        = $_POST['comportamientoMascota'] ?? null; 
    
    $alimento              = $_POST['alimentoMascota'] ?? null;
    $horario_alimentacion  = $_POST['horarioAlimentacion'] ?? null;
    $premios               = $_POST['premiosMascota'] ?? null;
    $suplementos           = $_POST['suplementosMascota'] ?? null;
    $agua                  = $_POST['aguaMascota'] ?? null;
    $alergias_alimentarias = $_POST['alergiasAlimentarias'] ?? null;

    $aseo                  = $_POST['aseoMascota'] ?? null;
    $restricciones_ejercicio = $_POST['restriccionesEjercicio'] ?? null;
    $temperamento          = $_POST['temperamentoMascota'] ?? null;
    $miedos                = $_POST['miedosMascota'] ?? null;
    $ubicacion_microchip   = $_POST['ubicacionMicrochip'] ?? null;
    $grupo_sanguineo       = $_POST['grupoSanguineoMascota'] ?? null;
    $reacciones_anestesia  = $_POST['reaccionesAnestesia'] ?? null;
    $deseos_final_vida     = $_POST['deseosFinalVida'] ?? null;

    // Prepara la consulta UPDATE para la tabla `mascotas`
    $stmt_update_mascota = $pdo->prepare("
        UPDATE mascotas SET
            raza = ?, sexo = ?, especie = ?, edad = ?, color = ?,
            microchip = ?, collar = ?,
            veterinario_habitual = ?, clinica_emergencia = ?, seguro_mascotas = ?,
            vacunas = ?, esterilizacion = ?, condiciones_medicas = ?,
            alergias = ?, medicamentos_preventivos = ?, cirugias = ?, hospitalizaciones = ?,
            comportamiento = ?, alimento = ?, horario_alimentacion = ?,
            premios = ?, suplementos = ?, agua = ?, alergias_alimentarias = ?,
            aseo = ?, restricciones_ejercicio = ?, temperamento = ?, miedos = ?,
            ubicacion_microchip = ?, grupo_sanguineo = ?, reacciones_anestesia = ?,
            deseos_final_vida = ?
        WHERE id_mascota = ?
    ");

    $stmt_update_mascota->execute([
        $raza, $sexo, $especie, $edad, $color,
        $microchip, $collar,
        $veterinario_habitual, $clinica_emergencia, $seguro_mascotas,
        $vacunas, $esterilizacion, $condiciones_medicas,
        $alergias, $medicamentos_preventivos, $cirugias, $hospitalizaciones,
        $comportamiento, $alimento, $horario_alimentacion,
        $premios, $suplementos, $agua, $alergias_alimentarias,
        $aseo, $restricciones_ejercicio, $temperamento, $miedos,
        $ubicacion_microchip, $grupo_sanguineo, $reacciones_anestesia,
        $deseos_final_vida,
        $id_mascota
    ]);

    // Si todo fue bien, confirma la transacción
    $pdo->commit();

    // Redirigir al usuario (veterinario) a 'empleado.php'
    // Asume que 'guardar_reporte.php' está en 'views/gestionMascotas/' y 'empleado.php' está en la raíz 'PetLittle/'
    header('Location: ../empleado/empleado.php');  
    exit();

} catch (\PDOException $e) {
    // Si algo sale mal, revertir la transacción
    $pdo->rollBack();
    error_log("Error al guardar el reporte o actualizar la mascota en la DB: " . $e->getMessage()); 
    echo "<script>alert('Error al guardar el reporte y actualizar la mascota: " . $e->getMessage() . "'); window.history.back();</script>";
    exit();
} catch (\Exception $e) {
    // Otros errores inesperados
    $pdo->rollBack();
    error_log("Error inesperado en guardar_reporte.php: " . $e->getMessage());
    echo "<script>alert('Ocurrió un error inesperado: " . $e->getMessage() . "'); window.history.back();</script>";
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="es">
</html>