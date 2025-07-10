<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Administración - Gestión de Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/usuariosVistaAdmin.css"> 
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="logo-container">
                <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
            </div>
        </div>
        <div class="titulo-header">
            <h1>Gestión de Clientes</h1>
        </div>
        <a href="../admin/perfilAdmin.php" id="btnVolver" class="btn-volver">&larr; Volver</a>
    </header>

    <main class="main-content">
        <section class="section-card">
            <h2><i class="fas fa-user-friends icon-spacing"></i> Listado de Clientes</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th class="column-telefono">Teléfono</th>
                            <th>Mascotas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
                <!-- Botón para descargar Excel -->
                <div class="exportar-excel">
                    <form action="../../controllers/exportarUsuariosMascotas.php" method="POST">
                        <button type="submit" class="btn-exportar-excel">
                        <i class="fas fa-file-excel"></i> Descargar Excel
                        </button>
                    </form>
                </div>

            </div>
        </section>
    </main>

    <script src="../../assets/js/adminUsuarios.js"></script>
    
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