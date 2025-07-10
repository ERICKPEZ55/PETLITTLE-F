<?php
require_once("../../configuracion/conexion.php");
$conexion = conexion();

$datosOrden = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE id_orden = ?");
    $stmt->execute([$id]);
    $datosOrden = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Para poder llenar los select
$propietarios = $conexion->query("SELECT id_usuario, nombre FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reordenar</title>
    <link rel="stylesheet" href="../../assets/css/ordenesLab.css">
</head>
<body>
    <h1>Crear nueva orden basada en la anterior</h1>
    <form method="POST" action="ordenesLaboratorio.php">
        <!-- Podrías incluso ocultar este input para que se rellene automáticamente -->
        <label>Paciente:</label>
        <input type="hidden" name="paciente" value="<?= $datosOrden['id_mascota'] ?>">
        <p><?= $datosOrden['id_mascota'] ?></p>

        <label>Tipo de muestra:</label>
        <input type="text" name="tipoMuestra" value="<?= $datosOrden['tipo_muestra'] ?>">

        <label>Pruebas:</label>
        <textarea name="pruebas"><?= $datosOrden['pruebas'] ?></textarea>

        <label>Laboratorio:</label>
        <input type="text" name="laboratorio" value="<?= $datosOrden['laboratorio_destino'] ?>">

        <label>Urgencia:</label>
        <select name="urgencia">
            <option value="normal" <?= $datosOrden['urgencia'] === "normal" ? "selected" : "" ?>>Normal</option>
            <option value="urgente" <?= $datosOrden['urgencia'] === "urgente" ? "selected" : "" ?>>Urgente</option>
        </select>

        <label>Notas:</label>
        <textarea name="notasClinicas"><?= $datosOrden['notas'] ?></textarea>

        <button type="submit">Crear Orden</button>
    </form>
    <a href="ordenesLaboratorio.php">← Volver</a>

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
