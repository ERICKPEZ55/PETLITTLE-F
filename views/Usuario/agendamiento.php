<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de Usuario - Veterinaria</title>
    <link rel="stylesheet" href="../../assets/css/agendamiento.css" />
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../../assets/img/Logo negro.png" alt="Logo" class="logo" />
        </div>
        <h1 class="titulo-header">Inicio</h1>
    </header>

    <div class="contenedor">
        <!-- PERFIL SOBRE EL LATERAL -->
        <div class="perfil-sobre-menu">
            <div class="info-perfil">
                <p class="nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
                <p class="rol">Plan básico de salud</p>
                <a href="editarPerfilUsuario.php">Editar mi información</a>
            </div>
        </div>

        <aside class="menu-lateral">
            <nav class="menu">
                <a href="agendamiento.php">Inicio</a>
                <a href="./misMascotas.php">Mis mascotas</a>
                <a href="../gestionCitas/tablasCitas.php">Cancelar citas</a>
                <a href="../../models/logout.php" class="cerrar-sesion">Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="contenido">
            <img src="../../assets/img/img 1.png" alt="doctor cargando perros" class="img1" />
            <h2>Bienvenido</h2>
            <p>Aquí encuentras todas las opciones del plan de salud disponible para tu mascota.</p>

            <div class="opciones">
                <div class="opcion">
                    <img src="../../assets/img/imga1.png" alt="calendario" class="calendarioimg" />
                    <h3>Agendar citas</h3>
                    <button onclick="window.location.href='../usuario/agendamientoCalendario.php'">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/imga2.png" alt="calendario" class="calendarioimg" />
                    <h3>Citas agendadas</h3>
                    <button onclick="window.location.href='../gestionCitas/tablasCitas.php'">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/imga3.png" alt="calendario" class="calendarioimg" />
                    <h3>Órdenes pendientes</h3>
                    <button onclick="window.location.href='../usuario/ordenesPendientes.php'">Ingresar</button>
                </div>
                <div class="opcion">
                    <img src="../../assets/img/imga4.png" alt="calendario" class="calendarioimg" />
                    <h3>Laboratorio clínico</h3>
                    <button onclick="window.location.href='../usuario/laboratorios.php'">Ingresar</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Script para cerrar sesión tras inactividad -->
    <script>
        let timeoutInactivity;

        function cerrarSesionPorInactividad() {
            window.location.href = '../../models/logout.php';
        }

        function reiniciarTemporizador() {
            clearTimeout(timeoutInactivity);
            timeoutInactivity = setTimeout(cerrarSesionPorInactividad, 10000000); // 10 segundos
        }

        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeydown = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
    </script>
</body>
</html>
