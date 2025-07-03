<?php
require_once("../../configuracion/conexion.php");
$conexion = conexion();

$orden = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("
        SELECT o.*, m.nombre AS nombre_mascota, m.raza, CONCAT(u.nombre, ' ', u.apellido) AS propietario
        FROM ordenes_laboratorio o
        JOIN mascotas m ON o.id_mascota = m.id_mascota
        JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE o.id_orden = ?
    ");
    $stmt->execute([$id]);
    $orden = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Orden</title>
    <link rel="stylesheet" href="../../assets/css/verOrdenLaboratorio.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Encabezado -->
<header class="header">
  <div class="logo-container">
    <img src="../../assets/img/logo negativo.png" alt="Logo">
  </div>
  <div class="titulo-header">
    <h1>Detalles de la Orden de Laboratorio</h1>
  </div>
</header>

<!-- Contenido principal -->
<main class="container">
    <?php if ($orden): ?>
        <h2>Información de la Orden</h2>
        <div class="datos-orden">
            <div class="dato">
                <label>Paciente:</label>
                <span><?= $orden["nombre_mascota"] ?> (<?= $orden["raza"] ?>)</span>
            </div>
            <div class="dato">
                <label>Propietario:</label>
                <span><?= $orden["propietario"] ?></span>
            </div>
            <div class="dato">
                <label>Tipo de muestra:</label>
                <span><?= $orden["tipo_muestra"] ?></span>
            </div>
            <div class="dato">
                <label>Pruebas:</label>
                <span><?= $orden["pruebas"] ?></span>
            </div>
            <div class="dato">
                <label>Laboratorio destino:</label>
                <span><?= $orden["laboratorio_destino"] ?></span>
            </div>
            <div class="dato">
                <label>Urgencia:</label>
                <span><?= $orden["urgencia"] ?></span>
            </div>
            <div class="dato" style="grid-column: span 2;">
                <label>Notas clínicas:</label>
                <span><?= $orden["notas"] ?></span>
            </div>
            <div class="dato">
                <label>Fecha de creación:</label>
                <span><?= $orden["fecha_creacion"] ?></span>
            </div>
        </div>
    <?php else: ?>
        <h2>Orden no encontrada</h2>
        <p>La orden solicitada no existe o ha sido eliminada.</p>
    <?php endif; ?>

    <div class="btn-volver-center">
        <a href="ordenesLaboratorio.php">← Volver al historial</a>
    </div>
</main>

</body>
</html>
