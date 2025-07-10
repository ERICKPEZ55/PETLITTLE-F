<?php
require_once '../../configuracion/conexion.php';

$pdo = conexion();

header('Content-Type: application/json');

$petId = $_GET['pet_id'] ?? null;

if (!$petId) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['error' => 'ID de mascota no proporcionado.']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT
            m.id_mascota AS id,
            m.nombre, m.especie, m.raza, m.sexo, m.edad, m.color, m.microchip, m.collar,
            u.nombre AS nombre_dueno,
            u.apellido AS apellido_dueno,
            u.direccion AS direccionDueño,
            u.telefono AS telefonoDueño,
            u.correo AS emailDueño,
            m.veterinario_habitual AS veterinario,
            m.clinica_emergencia AS clinicaEmergencia,
            m.seguro_mascotas AS seguroMascotas,
            m.vacunas, m.esterilizacion,
            m.condiciones_medicas AS condicionesMedicas,
            m.alergias,
            m.medicamentos_preventivos AS medicamentosPreventivos,
            m.cirugias, m.hospitalizaciones, m.comportamiento,
            m.alimento, m.horario_alimentacion AS horarioAlimentacion,
            m.premios, m.suplementos, m.agua,
            m.alergias_alimentarias AS alergiasAlimentarias,
            m.aseo, m.restricciones_ejercicio AS restriccionesEjercicio,
            m.temperamento, m.miedos,
            m.ubicacion_microchip AS ubicacionMicrochip,
            m.grupo_sanguineo AS grupoSanguineo,
            m.reacciones_anestesia AS reaccionesAnestesia,
            m.deseos_final_vida AS deseosFinalVida
        FROM
            mascotas m
        JOIN
            usuarios u ON m.id_usuario = u.id_usuario
        WHERE
            m.id_mascota = ?
    ");
    $stmt->execute([$petId]);
    $petData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($petData) {
        $petData['nombreDueñoContacto'] = ($petData['nombre_dueno'] ?? '') . ' ' . ($petData['apellido_dueno'] ?? '');
        $petData['contactoEmergencia'] = ''; // Pendiente si agregas esas columnas

        http_response_code(200); // ¡Todo bien!
        echo json_encode($petData);
    } else {
        http_response_code(404); // No encontrado
        echo json_encode(['error' => 'Mascota no encontrada o sin datos.']);
    }

} catch (\PDOException $e) {
    error_log("Error de base de datos al obtener datos de mascota: " . $e->getMessage());

    http_response_code(500); // Error interno
    echo json_encode(['error' => 'Error de base de datos al obtener datos de mascota.']);
}
