<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Detallado de Mascota</title>
    <link rel="stylesheet" href="../../assets/css/perfil.css">
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
                    <img id="imagenMascota" src="<?php echo htmlspecialchars($pet['imagen'] ?? ''); ?>" alt="Foto de la Mascota">
                </div>
                <div class="pet-details-brief">
                    <h2 id="nombreMascota" class="pet-name"><?php echo htmlspecialchars($pet['nombre'] ?? ''); ?></h2>
                    <p class="pet-breed"><strong class="label">Raza:</strong> <span id="razaMascota"><?php echo htmlspecialchars($pet['raza'] ?? ''); ?></span></p>
                    <p class="pet-owner"><strong class="label">Dueño:</strong> <span id="nombreDueño"><?php echo htmlspecialchars($pet['dueño'] ?? ''); ?></span></p>
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
                    <p><strong class="label">Nombre(s):</strong> <span id="nombreDueñoContacto"><?php echo htmlspecialchars($pet['nombreDueñoContacto'] ?? ''); ?></span></p>
                    <p><strong class="label">Dirección:</strong> <span id="direccionDueño"><?php echo htmlspecialchars($pet['direccionDueño'] ?? ''); ?></span></p>
                    <p><strong class="label">Teléfonos:</strong> <span id="telefonoDueño"><?php echo htmlspecialchars($pet['telefonoDueño'] ?? ''); ?></span></p>
                    <p><strong class="label">Email:</strong> <span id="emailDueño"><?php echo htmlspecialchars($pet['emailDueño'] ?? ''); ?></span></p>
                    <p><strong class="label">Emergencia - Contacto:</strong> <span id="contactoEmergencia"><?php echo htmlspecialchars($pet['contactoEmergencia'] ?? ''); ?></span></p>
                    <p><strong class="label">Veterinario:</strong> <span id="veterinario"><?php echo htmlspecialchars($pet['veterinario'] ?? ''); ?></span></p>
                    <p><strong class="label">Clínica Emergencia:</strong> <span id="clinicaEmergencia"><?php echo htmlspecialchars($pet['clinicaEmergencia'] ?? ''); ?></span></p>
                    <p><strong class="label">Seguro:</strong> <span id="seguroMascotas"><?php echo htmlspecialchars($pet['seguroMascotas'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="medical-history">
                <h3 class="section-title">Historial Médico</h3>
                <div class="info-grid">
                    <p><strong class="label">Vacunas:</strong> <span id="vacunasMascota"><?php echo htmlspecialchars($pet['vacunas'] ?? ''); ?></span></p>
                    <p><strong class="label">Esterilización/Castración:</strong> <span id="esterilizacionMascota"><?php echo htmlspecialchars($pet['esterilizacion'] ?? ''); ?></span></p>
                    <p><strong class="label">Condiciones Médicas:</strong> <span id="condicionesMedicas"><?php echo htmlspecialchars($pet['condicionesMedicas'] ?? ''); ?></span></p>
                    <p><strong class="label">Alergias:</strong> <span id="alergiasMascota"><?php echo htmlspecialchars($pet['alergias'] ?? ''); ?></span></p>
                    <p><strong class="label">Medicamentos:</strong> <span id="medicamentosMascota"><?php echo htmlspecialchars($pet['medicamentos'] ?? ''); ?></span></p>
                    <p><strong class="label">Preventivos:</strong> <span id="medicamentosPreventivos"><?php echo htmlspecialchars($pet['medicamentosPreventivos'] ?? ''); ?></span></p>
                    <p><strong class="label">Cirugías:</strong> <span id="cirugiasMascota"><?php echo htmlspecialchars($pet['cirugias'] ?? ''); ?></span></p>
                    <p><strong class="label">Hospitalizaciones:</strong> <span id="hospitalizacionesMascota"><?php echo htmlspecialchars($pet['hospitalizaciones'] ?? ''); ?></span></p>
                    <p><strong class="label">Comportamiento:</strong> <span id="comportamientoMascota"><?php echo htmlspecialchars($pet['comportamiento'] ?? ''); ?></span></p>
                    <p><strong class="label">Ejercicio:</strong> <span id="ejercicioMascota"><?php echo htmlspecialchars($pet['ejercicio'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="dietary-info">
                <h3 class="section-title">Información Dietética</h3>
                <div class="info-grid">
                    <p><strong class="label">Alimento:</strong> <span id="alimentoMascota"><?php echo htmlspecialchars($pet['alimento'] ?? ''); ?></span></p>
                    <p><strong class="label">Horario:</strong> <span id="horarioAlimentacion"><?php echo htmlspecialchars($pet['horarioAlimentacion'] ?? ''); ?></span></p>
                    <p><strong class="label">Premios:</strong> <span id="premiosMascota"><?php echo htmlspecialchars($pet['premios'] ?? ''); ?></span></p>
                    <p><strong class="label">Suplementos:</strong> <span id="suplementosMascota"><?php echo htmlspecialchars($pet['suplementos'] ?? ''); ?></span></p>
                    <p><strong class="label">Agua:</strong> <span id="aguaMascota"><?php echo htmlspecialchars($pet['agua'] ?? ''); ?></span></p>
                    <p><strong class="label">Alergias Alimentarias:</strong> <span id="alergiasAlimentarias"><?php echo htmlspecialchars($pet['alergiasAlimentarias'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="other-info">
                <h3 class="section-title">Otra Información</h3>
                <div class="info-grid">
                    <p><strong class="label">Aseo:</strong> <span id="aseoMascota"><?php echo htmlspecialchars($pet['aseo'] ?? ''); ?></span></p>
                    <p><strong class="label">Restricciones Ejercicio:</strong> <span id="restriccionesEjercicio"><?php echo htmlspecialchars($pet['restriccionesEjercicio'] ?? ''); ?></span></p>
                    <p><strong class="label">Temperamento:</strong> <span id="temperamentoMascota"><?php echo htmlspecialchars($pet['temperamento'] ?? ''); ?></span></p>
                    <p><strong class="label">Miedos/Fobias:</strong> <span id="miedosMascota"><?php echo htmlspecialchars($pet['miedos'] ?? ''); ?></span></p>
                    <p><strong class="label">Ubicación Microchip:</strong> <span id="ubicacionMicrochip"><?php echo htmlspecialchars($pet['ubicacionMicrochip'] ?? ''); ?></span></p>
                    <p><strong class="label">Grupo Sanguíneo:</strong> <span id="grupoSanguineoMascota"><?php echo htmlspecialchars($pet['grupoSanguineo'] ?? ''); ?></span></p>
                    <p><strong class="label">Reacciones Anestesia:</strong> <span id="reaccionesAnestesia"><?php echo htmlspecialchars($pet['reaccionesAnestesia'] ?? ''); ?></span></p>
                    <p><strong class="label">Deseos Final Vida:</strong> <span id="deseosFinalVida"><?php echo htmlspecialchars($pet['deseosFinalVida'] ?? ''); ?></span></p>
                </div>
            </section>

            <section class="upload-history">
                <h3 class="section-title">Descargar Reporte Medico</h3>
                <div class="upload-container">
                    <button onclick="subirHistorial()">Descargar PDF</button>
                </div>
            </section>

            <div class="navigation">
                <a href="javascript:history.back()" class="back-link">← Volver</a>
            </div>
        </div>
    </main>

    <script>
        function DescargarReporte() {
            const fileInput = document.getElementById('historialClinico');
            const file = fileInput.files[0];

            if (file) {
                alert(`Historial clínico ${file.name} subido correctamente.`);
                fileInput.value = ''; // Clear the input after "upload"
            } else {
                alert('Por favor, selecciona un archivo para subir.');
            }
        }
    </script>
</body>
</html>