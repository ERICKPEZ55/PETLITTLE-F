<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Reporte Médico Detallado</title>
    <link rel="stylesheet" href="../../assets/css/reportes.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Estilos específicos para este formulario, si es necesario */
        .volver-btn {
            background-color: #272A57; 
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .volver-btn:hover {
            background-color: #1c1e40; 
        }

        /* Estilos adicionales para los nuevos campos si no están en reportes.css */
        .form-section-title {
            background-color: #f0f0f0;
            padding: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
            border-left: 5px solid #1c2561;
            font-weight: bold;
            font-size: 1.1em;
        }
        .form-group.half {
            width: 48%; /* Para dos columnas */
            display: inline-block;
            vertical-align: top;
            margin-right: 2%;
        }
        .form-group.half:nth-child(2n) {
            margin-right: 0;
        }
        .form-group-full textarea {
            min-height: 80px; /* Altura mínima para textareas */
            resize: vertical; /* Permite redimensionar verticalmente */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
        </div>
        <h1 class="titulo-header">Registro de Reporte Médico Detallado</h1>
        <button type="button" class="volver-btn" onclick="goBackToEmpleado()">← Volver</button> 
    </div>
    <div class="container">
        <h2>Nuevo Reporte Médico</h2>
        <form id="reporteMascotaForm" method="POST" action="guardar_reporte.php" enctype="multipart/form-data"> 
            
            <div class="form-group-full">
                <label for="mascota">Seleccionar Mascota</label>
                <select id="mascota" name="id_mascota" required>
                    <option value="">-- Elegir --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha del Reporte</label>
                <input type="date" id="fecha" name="fechaReporte" required> 
            </div>
            
            <div class="form-section-title">Datos Generales de la Mascota</div>
            <div class="form-group half">
                <label for="razaMascota">Raza</label>
                <input type="text" id="razaMascota" name="raza"> 
            </div>
            <div class="form-group half">
                <label for="sexoMascota">Género</label>
                <select id="sexoMascota" name="sexo">
                    <option value="">-- Elegir --</option>
                    <option value="Macho">Macho</option>
                    <option value="Hembra">Hembra</option>
                </select>
            </div>
            <div class="form-group half">
                <label for="especieMascota">Especie</label>
                <input type="text" id="especieMascota" name="especie"> 
            </div>
            <div class="form-group half">
                <label for="edadMascota">Edad</label>
                <input type="text" id="edadMascota" name="edad" placeholder="Ej: 3 años, 6 meses">
            </div>
            <div class="form-group-full">
                <label for="colorMascota">Color y Marcas</label>
                <input type="text" id="colorMascota" name="color" placeholder="Ej: Marrón y blanco, manchas en el ojo izquierdo">
            </div>
            <div class="form-group half">
                <label for="microchipMascota">Microchip</label>
                <input type="text" id="microchipMascota" name="microchip" placeholder="Ej: 987654321098765">
            </div>
            <div class="form-group half">
                <label for="collarMascota">Collar/Placa</label>
                <input type="text" id="collarMascota" name="collar" placeholder="Ej: Collar azul con placa 'Firulais'">
            </div>

            <div class="form-section-title">Contacto del Dueño</div>
            <div class="form-group half">
                <label for="nombreDueñoContacto">Nombre(s) del Dueño</label>
                <input type="text" id="nombreDueñoContacto" name="nombreDueñoContacto"> 
            </div>

            <div class="form-group half">
                <label for="telefonoDueño">Teléfono del Dueño</label>
                <input type="tel" id="telefonoDueño" name="telefonoDueño" placeholder="Ej: +57 310 123 4567">
            </div>

            <div class="form-group-full">
                <label for="direccionDueño">Dirección del Dueño</label>
                <input type="text" id="direccionDueño" name="direccionDueño" placeholder="Ej: Calle Falsa 123, Ciudad">
            </div>

            <div class="form-group half">
                <label for="emailDueño">Email del Dueño</label>
                <input type="email" id="emailDueño" name="emailDueño" placeholder="Ej: ana.garcia@example.com">
            </div>
            
            <div class="form-group half">
                <label for="contactoEmergencia">Contacto de Emergencia</label>
                <input type="text" id="contactoEmergencia" name="contactoEmergencia" placeholder="Ej: Pedro García (Hermano) - 320 987 6543">
            </div>
            <div class="form-group half">
                <label for="veterinario">Veterinario Habitual</label>
                <input type="text" id="veterinario" name="veterinario" placeholder="Ej: Dra. María López">
            </div>
            <div class="form-group half">
                <label for="clinicaEmergencia">Clínica de Emergencia</label>
                <input type="text" id="clinicaEmergencia" name="clinicaEmergencia" placeholder="Ej: Clínica Veterinaria Central">
            </div>
            <div class="form-group-full">
                <label for="seguroMascotas">Seguro de Mascotas</label>
                <input type="text" id="seguroMascotas" name="seguroMascotas" placeholder="Ej: Mascotas Seguras S.A. - Póliza #ABCDE123">
            </div>

            <div class="form-section-title">Historial Médico y Comportamiento</div>
            <div class="form-group-full">
                <label for="sintomas">Síntomas Actuales (para este reporte)</label>
                <textarea id="sintomas" name="sintomas" required></textarea>
            </div>
            <div class="form-group-full">
                <label for="diagnostico">Diagnóstico (para este reporte)</label>
                <textarea id="diagnostico" name="diagnostico" required></textarea>
            </div>
            <div class="form-group-full">
                <label for="tratamiento">Tratamiento (para este reporte)</label>
                <textarea id="tratamiento" name="tratamiento" required></textarea>
            </div>
            <div class="form-group-full">
                <label for="medicamentosReporte">Medicamentos Recetados (para este reporte)</label>
                <textarea id="medicamentosReporte" name="medicamentos"></textarea> 
            </div>
            <div class="form-group-full">
                <label for="recomendaciones">Recomendaciones (para este reporte)</label>
                <textarea id="recomendaciones" name="recomendaciones"></textarea>
            </div>
            
            <div class="form-group-full">
                <label for="vacunasMascota">Vacunas</label>
                <textarea id="vacunasMascota" name="vacunas" placeholder="Ej: Al día: Rabia (Feb 2024), Séxtuple (Mar 2024)"></textarea>
            </div>
            <div class="form-group half">
                <label for="esterilizacionMascota">Esterilización/Castración</label>
                <input type="text" id="esterilizacionMascota" name="esterilizacion" placeholder="Ej: Sí, esterilizado (Enero 2023)">
            </div>
            <div class="form-group half">
                <label for="condicionesMedicas">Condiciones Médicas Preexistentes</label>
                <input type="text" id="condicionesMedicas" name="condicionesMedicas" placeholder="Ej: Ninguna conocida, chequeos regulares.">
            </div>
            <div class="form-group half">
                <label for="alergiasMascota">Alergias</label>
                <input type="text" id="alergiasMascota" name="alergias" placeholder="Ej: Polen">
            </div>
            <div class="form-group half">
                <label for="medicamentosPreventivos">Medicamentos Preventivos</label>
                <input type="text" id="medicamentosPreventivos" name="medicamentosPreventivos" placeholder="Ej: Desparasitación (mensual), Antipulgas (trimestral)">
            </div>
            <div class="form-group half">
                <label for="cirugiasMascota">Cirugías Previas</label>
                <input type="text" id="cirugiasMascota" name="cirugias" placeholder="Ej: Esterilización">
            </div>
            <div class="form-group half">
                <label for="hospitalizacionesMascota">Hospitalizaciones Previas</label>
                <input type="text" id="hospitalizacionesMascota" name="hospitalizaciones" placeholder="Ej: Ninguna">
            </div>
            <div class="form-group-full">
                <label for="comportamientoMascota">Comportamiento</label>
                <textarea id="comportamientoMascota" name="comportamiento" placeholder="Ej: Juguetón, amigable con niños y otros perros."></textarea>
            </div>

            <div class="form-section-title">Información Dietética</div>
            <div class="form-group half">
                <label for="alimentoMascota">Alimento</label>
                <input type="text" id="alimentoMascota" name="alimento" placeholder="Ej: Croquetas premium para perros adultos">
            </div>
            <div class="form-group half">
                <label for="horarioAlimentacion">Horario de Alimentación</label>
                <input type="text" id="horarioAlimentacion" name="horarioAlimentacion" placeholder="Ej: Dos veces al día: 8:00 AM y 6:00 PM">
            </div>
            <div class="form-group half">
                <label for="premiosMascota">Premios</label>
                <input type="text" id="premiosMascota" name="premios" placeholder="Ej: Galletas de arroz y zanahoria">
            </div>
            <div class="form-group half">
                <label for="suplementosMascota">Suplementos</label>
                <input type="text" id="suplementosMascota" name="suplementos" placeholder="Ej: Omega 3 (1 pastilla al día)">
            </div>
            <div class="form-group half">
                <label for="aguaMascota">Agua</label>
                <input type="text" id="aguaMascota" name="agua" placeholder="Ej: Agua fresca disponible todo el día">
            </div>
            <div class="form-group half">
                <label for="alergiasAlimentarias">Alergias Alimentarias</label>
                <input type="text" id="alergiasAlimentarias" name="alergiasAlimentarias" placeholder="Ej: Pollo">
            </div>

            <div class="form-section-title">Otra Información Relevante</div>
            <div class="form-group half">
                <label for="aseoMascota">Aseo</label>
                <input type="text" id="aseoMascota" name="aseo" placeholder="Ej: Cepillado diario, baño cada 2 meses">
            </div>
            <div class="form-group half">
                <label for="restriccionesEjercicio">Restricciones de Ejercicio</label>
                <input type="text" id="restriccionesEjercicio" name="restriccionesEjercicio" placeholder="Ej: Evitar ejercicio intenso en horas de sol">
            </div>
            <div class="form-group half">
                <label for="temperamentoMascota">Temperamento</label>
                <input type="text" id="temperamentoMascota" name="temperamento" placeholder="Ej: Sociable, leal, un poco protector">
            </div>
            <div class="form-group half">
                <label for="miedosMascota">Miedos/Fobias</label>
                <input type="text" id="miedosMascota" name="miedos" placeholder="Ej: Truenos, fuegos artificiales">
            </div>
            <div class="form-group half">
                <label for="ubicacionMicrochip">Ubicación del Microchip</label>
                <input type="text" id="ubicacionMicrochip" name="ubicacionMicrochip" placeholder="Ej: Entre los omóplatos">
            </div>
            <div class="form-group half">
                <label for="grupoSanguineoMascota">Grupo Sanguíneo</label>
                <input type="text" id="grupoSanguineoMascota" name="grupoSanguineo" placeholder="Ej: DEA 1.1 Positivo">
            </div>
            <div class="form-group half">
                <label for="reaccionesAnestesia">Reacciones a la Anestesia</label>
                <input type="text" id="reaccionesAnestesia" name="reaccionesAnestesia" placeholder="Ej: Ninguna conocida">
            </div>
            <div class="form-group half">
                <label for="deseosFinalVida">Deseos para el Final de Vida</label>
                <input type="text" id="deseosFinalVida" name="deseosFinalVida" placeholder="Ej: Discutido con veterinario, optar por eutanasia si hay sufrimiento.">
            </div>

            <div class="form-group-full">
                <label for="archivo">Adjuntar archivo (opcional)</label>
                <input type="file" id="archivo" name="archivoAdjunto">
            </div>

            <div class="form-group-full" style="display: flex; justify-content: flex-start; margin-top: 20px; gap: 10px;">
                <button id="btnGuardarReporte" type="submit" class="submit-btn">Guardar Reporte</button> 
                </div>
        </form> 
    </div>
    
    <script>
        // Cambiamos el nombre de la función para que no haya confusión y sea más descriptivo
        function goBackToEmpleado() {
            // La ruta a empleado.php dependerá de dónde se encuentre este archivo HTML
            // Si este archivo está en views/gestionMascotas/ y empleado.php está en la raíz PetLittle/empleado.php
            // La ruta correcta sería '../../empleado.php'
            // Si empleado.php está en views/empleado/empleado.php, entonces la ruta sería '../empleado/empleado.php'
            window.location.href = '../../views/empleado/empleado.php'; // Ajusta esta ruta si es necesario
        }

        // Función para poblar el formulario con los datos de la mascota seleccionada
        async function loadPetData(petId) {
            if (!petId) {
                // Si no hay mascota seleccionada, limpia los campos
                // Es importante limpiar solo los campos de información de la mascota/dueño
                // para que el veterinario pueda introducir un nuevo reporte médico.
                document.getElementById('razaMascota').value = '';
                document.getElementById('sexoMascota').value = '';
                document.getElementById('especieMascota').value = '';
                document.getElementById('edadMascota').value = '';
                document.getElementById('colorMascota').value = '';
                document.getElementById('microchipMascota').value = '';
                document.getElementById('collarMascota').value = '';
                document.getElementById('nombreDueñoContacto').value = '';
                document.getElementById('telefonoDueño').value = '';
                document.getElementById('direccionDueño').value = '';
                document.getElementById('emailDueño').value = '';
                document.getElementById('contactoEmergencia').value = '';
                document.getElementById('veterinario').value = '';
                document.getElementById('clinicaEmergencia').value = '';
                document.getElementById('seguroMascotas').value = '';
                document.getElementById('vacunasMascota').value = '';
                document.getElementById('esterilizacionMascota').value = '';
                document.getElementById('condicionesMedicas').value = '';
                document.getElementById('alergiasMascota').value = '';
                document.getElementById('medicamentosPreventivos').value = '';
                document.getElementById('cirugiasMascota').value = '';
                document.getElementById('hospitalizacionesMascota').value = '';
                document.getElementById('comportamientoMascota').value = '';
                document.getElementById('alimentoMascota').value = '';
                document.getElementById('horarioAlimentacion').value = '';
                document.getElementById('premiosMascota').value = '';
                document.getElementById('suplementosMascota').value = '';
                document.getElementById('aguaMascota').value = '';
                document.getElementById('alergiasAlimentarias').value = '';
                document.getElementById('aseoMascota').value = '';
                document.getElementById('restriccionesEjercicio').value = '';
                document.getElementById('temperamentoMascota').value = '';
                document.getElementById('miedosMascota').value = '';
                document.getElementById('ubicacionMicrochip').value = '';
                document.getElementById('grupoSanguineoMascota').value = '';
                document.getElementById('reaccionesAnestesia').value = '';
                document.getElementById('deseosFinalVida').value = '';

                // Los campos del reporte médico actual SÍ deben limpiarse si la mascota cambia a "Elegir"
                document.getElementById('sintomas').value = '';
                document.getElementById('diagnostico').value = '';
                document.getElementById('tratamiento').value = '';
                document.getElementById('medicamentosReporte').value = '';
                document.getElementById('recomendaciones').value = '';
                // También limpia la fecha
                document.getElementById('fecha').value = '';

                return;
            }

            try {
                // Asegúrate de que la ruta a 'get_pet_data.php' sea correcta desde la ubicación de este archivo HTML
                // Si este archivo está en views/gestionMascotas/ y get_pet_data.php está en la misma carpeta:
                const response = await fetch(`get_pet_data.php?pet_id=${petId}`);
                
                // Si la respuesta no es OK (por ejemplo, 404, 500), lanzar un error
                if (!response.ok) {
                    const errorText = await response.text(); 
                    throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
                }

                const data = await response.json();

                if (data.error) {
                    console.error('Error al cargar datos de la mascota:', data.error);
                    alert('Error al cargar los datos de la mascota: ' + data.error);
                    return;
                }

                // Poblar los campos del formulario con los datos recibidos
                // Asegúrate de que los 'id' de tus elementos HTML coincidan con los 'AS' de tu SELECT en get_pet_data.php
                document.getElementById('razaMascota').value = data.raza || '';
                document.getElementById('sexoMascota').value = data.sexo || ''; // Recuerda que 'genero' ahora es 'sexo' en DB
                document.getElementById('especieMascota').value = data.especie || '';
                document.getElementById('edadMascota').value = data.edad || '';
                document.getElementById('colorMascota').value = data.color || '';
                document.getElementById('microchipMascota').value = data.microchip || '';
                document.getElementById('collarMascota').value = data.collar || '';

                // Contacto del Dueño
                // Asegúrate de que tu get_pet_data.php devuelve estas claves si las estás obteniendo con un JOIN
                document.getElementById('nombreDueñoContacto').value = (data.nombre_dueno ? data.nombre_dueno + ' ' + data.apellido_dueno : '') || ''; 
                document.getElementById('telefonoDueño').value = data.telefono_dueno || '';
                document.getElementById('direccionDueño').value = data.direccion_dueno || '';
                document.getElementById('emailDueño').value = data.correo_dueno || '';
                // Estos campos pueden necesitar ajuste en get_pet_data.php si no los devuelves directamente
                document.getElementById('contactoEmergencia').value = data.contacto_emergencia || ''; 
                document.getElementById('veterinario').value = data.veterinario_habitual || '';
                document.getElementById('clinicaEmergencia').value = data.clinica_emergencia || '';
                document.getElementById('seguroMascotas').value = data.seguro_mascotas || '';

                // Historial Médico y Comportamiento (campos permanentes)
                document.getElementById('vacunasMascota').value = data.vacunas || '';
                document.getElementById('esterilizacionMascota').value = data.esterilizacion || '';
                document.getElementById('condicionesMedicas').value = data.condiciones_medicas || '';
                document.getElementById('alergiasMascota').value = data.alergias || '';
                document.getElementById('medicamentosPreventivos').value = data.medicamentos_preventivos || '';
                document.getElementById('cirugiasMascota').value = data.cirugias || '';
                document.getElementById('hospitalizacionesMascota').value = data.hospitalizaciones || '';
                document.getElementById('comportamientoMascota').value = data.comportamiento || '';

                // Información Dietética
                document.getElementById('alimentoMascota').value = data.alimento || '';
                document.getElementById('horarioAlimentacion').value = data.horario_alimentacion || '';
                document.getElementById('premiosMascota').value = data.premios || '';
                document.getElementById('suplementosMascota').value = data.suplementos || '';
                document.getElementById('aguaMascota').value = data.agua || '';
                document.getElementById('alergiasAlimentarias').value = data.alergias_alimentarias || '';

                // Otra Información Relevante
                document.getElementById('aseoMascota').value = data.aseo || '';
                document.getElementById('restriccionesEjercicio').value = data.restricciones_ejercicio || '';
                document.getElementById('temperamentoMascota').value = data.temperamento || '';
                document.getElementById('miedosMascota').value = data.miedos || '';
                document.getElementById('ubicacionMicrochip').value = data.ubicacion_microchip || '';
                document.getElementById('grupoSanguineoMascota').value = data.grupo_sanguineo || '';
                document.getElementById('reaccionesAnestesia').value = data.reacciones_anestesia || '';
                document.getElementById('deseosFinalVida').value = data.deseos_final_vida || '';

                // Los campos de Síntomas, Diagnóstico, Tratamiento, Medicamentos Recetados
                // y Recomendaciones son para el reporte actual, NO deben ser llenados automáticamente
                // Los dejamos vacíos o puedes limpiarlos explícitamente si prefieres
                document.getElementById('sintomas').value = '';
                document.getElementById('diagnostico').value = '';
                document.getElementById('tratamiento').value = '';
                document.getElementById('medicamentosReporte').value = '';
                document.getElementById('recomendaciones').value = '';
                // Y la fecha del reporte también debe estar vacía para un nuevo reporte
                document.getElementById('fecha').value = '';


            } catch (error) {
                console.error('Error al obtener datos de la mascota:', error);
                alert('Hubo un error al cargar los datos de la mascota. Por favor, inténtelo de nuevo.');
            }
        }

        // Función para cargar las mascotas en el select al iniciar la página
        document.addEventListener('DOMContentLoaded', async () => {
            const mascotaSelect = document.getElementById('mascota');
            
            try {
                // Ajusta la ruta a 'get_pets_list.php' si no está en la misma carpeta
                const response = await fetch('get_pets_list.php'); 
                
                // Si la respuesta no es OK, lanzar un error
                if (!response.ok) {
                    const errorText = await response.text(); 
                    throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);

                }

                const data = await response.json();

                if (data.success) {
                    data.pets.forEach(pet => {
                        const option = document.createElement('option');
                        option.value = pet.id_mascota;
                        option.textContent = pet.nombre;
                        mascotaSelect.appendChild(option);
                    });
                    // Opcional: Si hay un ID de mascota en la URL (ej. al editar), seleccionarlo
                    const urlParams = new URLSearchParams(window.location.search);
                    const initialPetId = urlParams.get('id_mascota');
                    if (initialPetId) {
                        mascotaSelect.value = initialPetId;
                        loadPetData(initialPetId); // Cargar datos si ya hay una mascota seleccionada desde la URL
                    }
                } else {
                    console.error('Error al cargar la lista de mascotas:', data.error);
                    alert('Error al cargar la lista de mascotas: ' + data.error);
                }
            } catch (error) {
                console.error('Error de conexión al cargar mascotas:', error);
                alert('No se pudo conectar para cargar la lista de mascotas.');
            }

            // Añadir el evento change al select para cargar los datos de la mascota
            mascotaSelect.addEventListener('change', (event) => {
                const selectedPetId = event.target.value;
                loadPetData(selectedPetId);
            });

            document.addEventListener('DOMContentLoaded', () => {
            const btnGuardarReporte = document.getElementById('btnGuardarReporte');

            btnGuardarReporte.addEventListener('click', async (e) => {
                e.preventDefault();

                // Aquí puedes colocar la lógica para guardar el reporte, por ejemplo usando fetch
                // Supongamos que estás enviando un formulario con los datos del reporte:
                const formData = new FormData(document.getElementById('formularioReporte')); // Asegúrate que tu formulario tenga ese id

                try {
                    const response = await fetch('guardar_reporte.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert("El reporte se creó exitosamente");
                        // Opcional: redirigir después de guardar
                        goBackToEmpleado();
                    } else {
                        alert("Hubo un error al guardar el reporte: " + result.error);
                    }
                } catch (error) {
                    console.error('Error al guardar el reporte:', error);
                    alert("Ocurrió un error al guardar el reporte. Intenta nuevamente.");
                }
            });
        });

            
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
            timeoutInactivity = setTimeout(cerrarSesionPorInactividad, 300000); // 10 minutos (ajustable)
        }

        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeydown = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
    </script>
</body>
</html>