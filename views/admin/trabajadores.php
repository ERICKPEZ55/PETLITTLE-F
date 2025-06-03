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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="../../assets/css/stylesTrabajadores.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="logo">
        <img src="../../assets/img/logo negativo.png" alt="">
    </div>
    <input type="text" placeholder="🔍 Buscar" id="buscar">
    <div class="notificaciones">🔔 <span id="notiCount">0</span></div>
    <div class="usuario">
        <img src="../../assets/img/Ellipse 3.png" alt="Admin">
        <p class="nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
    </div>
</header>

<main>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Rol</th>
                <th>Usuario</th>
                <th>Teléfono</th>
                <th>Contraseña</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody id="tablaUsuarios">
            <!-- Filas se agregarán dinámicamente -->
        </tbody>
    </table>

    <button id="btnAgregar">+ Agregar usuario</button>
</main>

<!-- Modal de Agregar Usuario -->
<div id="modalAgregar" class="modal">
    <div class="modal-content">
        <h2>Agregar Usuario</h2>
        <input type="text" id="nuevoNombre" placeholder="Nombre" required>
        <input type="text" id="nuevoApellido" placeholder="Apellido" required>
        <input type="text" id="rol" value="Empleado" readonly>

        <input type="text" id="nuevoTelefono" placeholder="Teléfono" maxlength="10"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        <button id="guardarUsuario">Agregar</button>
        <button id="cerrarAgregar">Cancelar</button>
    </div>
</div>

<!-- Modal de Edición -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <h2>Editar Usuario</h2>
        <input type="text" id="editNombre" placeholder="Nombre" required>
        <input type="text" id="editApellido" placeholder="Apellido" required>
        <input type="text" id="editRol" value="Empleado" readonly>
        <input type="text" id="editTelefono" placeholder="Teléfono" maxlength="10"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        <button id="guardarCambios">Guardar</button>
        <button id="cerrarModal">Cancelar</button>
    </div>
</div>

<script src="../../assets/js/trabajadores.js"></script>

</body>
</html>