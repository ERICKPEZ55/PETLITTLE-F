<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - PetLittle</title>
    <link rel="stylesheet" href="../../assets/css/tablas-citas.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
</head>
<body> 

    <header>
        <div class="logo">
            <img src="../../assets/img/logo negativo.png" alt="PetLittle" class="logonav">
        </div>
        <div class="buscador">
            <span class="icono-lupa">🔍</span>
            <input type="text" placeholder="Buscar">
        </div>
        <div class="notificaciones" onclick="mostrarNotificaciones()">🔔 <span id="notiCount">0</span></div>
        <div class="usuario-info">
            <img src="../../assets/img/admin.png" alt="Admin" class="adminimg">
            <span class="textousuario">Lauraz</span>
        </div>
        
    </header>
    

    <aside>
        <ul>
            <li><a href="../Usuario/agendamiento.php">Perfil</a></li>
            <li>Tabla agendamientos</li>
            <li>Ordenes pendientes</li>
        </ul>
    </aside>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Nombre Mascota</th>
                    <th>Cita</th>
                    <th>Fecha y hora</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="tablaCitas">
                
            </tbody>
        </table>

        <button id="btnAgregar">+ boton de ejemplo</button>
    </main>

    
    <div id="modalAgregar" class="modal">
        <div class="modal-content">
            <h2 id="tituloModal">Agendar Cita</h2>
            <input type="text" id="nombreCliente" placeholder="Nombre del cliente">
            <input type="email" id="correoCliente" placeholder="Correo">
            <input type="text" id="telefonoCliente" placeholder="Teléfono">
            <input type="text" id="nombreMascota" placeholder="Nombre de la mascota">
            <select id="tipoCita">
                <option value="Vacunación">Vacunación</option>
                <option value="Desparasitación">Desparasitación</option>
                <option value="Consulta general">Consulta general</option>
                <option value="Cirugía menor">Cirugía menor</option>
                <option value="Control anual">Control anual</option>
                <option value="Consulta dental">Consulta dental</option>
            </select>
            <input type="datetime-local" id="fechaHora">
            <button id="guardarCita">Guardar</button>
            <button id="cerrarAgregar">Cancelar</button>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>

</body>
</html>
