<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Panel de Empleado - Veterinaria</title>
    <link rel="stylesheet" href="../../assets/css/agendamiento.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../../assets/img/Logo negativo.png" alt="Logo" class="logo" />
        </div>
        <h1 class="titulo-header">Agendamiento</h1>
    </header>

    <div class="contenedor">
        <!-- PERFIL SOBRE EL LATERAL -->
        <div class="perfil-sobre-menu">
            <img src="../../assets/img/admin.png" alt="Empleado" class="foto-perfil" />
            <div class="info-perfil">
                
                <p class="rol">Plan básico de salud</p>
            </div>
        </div>

        <aside class="menu-lateral">
            <nav class="menu">
                <a href="#">Inicio</a>
                <a href="mascotascliente.html">Mis mascotas</a>
                <a href="tablas-citas.html">Cancelar citas</a>
                <a href="#">Historia clínica</a>
                <a href="#">Notificaciones</a>
                <a href=""../../models/logout.php" class="cerrar-sesion"" class="cerrar-sesion">Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="contenido">
            <img src="../../assets/img/img 1.png" alt="doctor cargando perros" class="img1" />
            <h2>Bienvenido</h2>
            <p>Aquí encuentras todas las opciones del plan de salud disponible para tu mascota.</p>

            <div class="opciones">
                <div class="opcion">
                    <img src="../../assets/img/calendarioblanco.png" alt="calendario" class="calendarioimg" />
                    <h3>Agendar citas</h3>
                    <button onclick="window.location.href='agendamientocalendario.html'">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/calendariocitasagendadas.png" alt="calendario" class="calendarioimg" />
                    <h3>Citas agendadas</h3>
                    <button onclick="window.location.href='../Gestion_citas/tablas-citas.html'">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/ordenespendienteslogo.png" alt="calendario" class="calendarioimg" />
                    <h3>Órdenes pendientes</h3>
                    <button onclick="window.location.href=''">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/laboratorioclinicologo.png" alt="calendario" class="calendarioimg" />
                    <h3>Laboratorio clínico</h3>
                    <button>Ingresar</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Script para cerrar sesión tras inactividad (lado del cliente) -->
    <script>
        let timeoutInactivity;

        function cerrarSesionPorInactividad() {
            window.location.href = '../login/logout.php'; // 🔐 Redirige al backend para cerrar sesión real
        }

        function reiniciarTemporizador() {
            clearTimeout(timeoutInactivity);
            timeoutInactivity = setTimeout(cerrarSesionPorInactividad, 10000); // ⏱️ CAMBIA AQUÍ TIEMPO CLIENTE (milisegundos)
        }

        // Detecta interacción del usuario
        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeydown = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
    </script>
</body>
</html>