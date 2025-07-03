<?php
require_once("../../configuracion/conexion.php");
$conexion = conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idMascota = $_POST["paciente"];
    $tipoMuestra = $_POST["tipoMuestra"];
    $pruebas = $_POST["pruebas"];
    $laboratorio = $_POST["laboratorio_destino"];
    $urgencia = $_POST["urgencia"];
    $notasClinicas = $_POST["notasClinicas"];

    $stmtInsert = $conexion->prepare("
        INSERT INTO ordenes_laboratorio (id_mascota, tipo_muestra, pruebas, laboratorio_destino, urgencia, notas)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmtInsert->execute([$idMascota, $tipoMuestra, $pruebas, $laboratorio, $urgencia, $notasClinicas]);
    header("Location: ordenesLaboratorio.php?exito=1");
    exit();
}

// Obtener lista de propietarios con nombre y apellido
$propietarios = $conexion->query("SELECT id_usuario, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);

// Si hay una orden a reordenar
$datosReorden = null;
$duenoReorden = null;
if (isset($_GET['reordenar']) && is_numeric($_GET['reordenar'])) {
    $idOrden = $_GET['reordenar'];
    $stmt = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE id_orden = ?");
    $stmt->execute([$idOrden]);
    $datosReorden = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener el dueño de la mascota
    if ($datosReorden) {
        $idMascota = $datosReorden['id_mascota'];
        $duenoReorden = $conexion->query("SELECT id_usuario FROM mascotas WHERE id_mascota = $idMascota")->fetchColumn();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de Laboratorio</title>
    <link rel="stylesheet" href="../../assets/css/ordenesLabs.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header class="header">
    <div class="header-left">
        <div class="logo-container">
            <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
        </div>
    </div>
    <div class="titulo-header">
        <h1>Órdenes de Laboratorio</h1>
    </div>
    <a href="../empleado/empleado.php" class="btn-volver">&larr; Volver</a>
</header>

<main class="main-content">
    <!-- FORMULARIO -->
    <section class="section-card">
        <h2><i class="fas fa-flask icon-spacing"></i> Crear Nueva Orden de Laboratorio</h2>

        <form class="lab-order-form" method="POST">
            <div class="form-group">
                <label for="propietario">Propietario:</label>
                <select id="propietario" name="propietario" required>
                    <option value="">Seleccione un propietario</option>
                    <?php foreach ($propietarios as $prop): ?>
                        <option value="<?= $prop['id_usuario'] ?>"
                            <?= ($datosReorden && $prop['id_usuario'] == $duenoReorden) ? 'selected' : '' ?>>
                            <?= $prop['nombre_completo'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="paciente">Paciente:</label>
                <select id="paciente" name="paciente" required>
                    <option value="">Seleccione un paciente</option>
                    <?php if ($datosReorden): ?>
                        <?php
                        $pacienteId = $datosReorden['id_mascota'];
                        $pacienteNombre = $conexion->query("SELECT nombre FROM mascotas WHERE id_mascota = $pacienteId")->fetchColumn();
                        echo "<option value='$pacienteId' selected>$pacienteNombre</option>";
                        ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tipoMuestra">Tipo de Muestra:</label>
                <select id="tipoMuestra" name="tipoMuestra" required>
                    <option value="">Seleccione</option>
                    <?php
                    $tipos = ['sangre', 'orina', 'heces', 'tejido', 'frotis', 'otro'];
                    foreach ($tipos as $tipo) {
                        $sel = ($datosReorden && $datosReorden['tipo_muestra'] == $tipo) ? 'selected' : '';
                        echo "<option value='$tipo' $sel>" . ucfirst($tipo) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="pruebas">Pruebas a Solicitar:</label>
                <textarea id="pruebas" name="pruebas" rows="3"><?= $datosReorden['pruebas'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="laboratorio_destino">Laboratorio Destino:</label>
                <select id="laboratorio_destino" name="laboratorio_destino" required>
                    <option value="">Seleccione</option>
                    <option value="labA" <?= ($datosReorden && $datosReorden['laboratorio_destino'] == 'labA') ? 'selected' : '' ?>>Laboratorio Central A</option>
                    <option value="labB" <?= ($datosReorden && $datosReorden['laboratorio_destino'] == 'labB') ? 'selected' : '' ?>>Laboratorio Diagnóstico B</option>
                </select>
            </div>

            <div class="form-group">
                <label for="urgencia">Urgencia:</label>
                <select id="urgencia" name="urgencia">
                    <option value="normal" <?= ($datosReorden && $datosReorden['urgencia'] == 'normal') ? 'selected' : '' ?>>Normal</option>
                    <option value="urgente" <?= ($datosReorden && $datosReorden['urgencia'] == 'urgente') ? 'selected' : '' ?>>Urgente</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notasClinicas">Notas Clínicas Relevantes:</label>
                <textarea id="notasClinicas" name="notasClinicas" rows="4"><?= $datosReorden['notas'] ?? '' ?></textarea>
            </div>

            <button type="submit" class="btn-primary-action">
                <i class="fas fa-plus-circle icon-spacing"></i> Crear Orden
            </button>
        </form>
    </section>

    <!-- HISTORIAL -->
    <section class="section-card">
        <h2><i class="fas fa-clipboard-list icon-spacing"></i> Historial de Órdenes de Laboratorio</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Propietario</th>
                        <th>Pruebas</th>
                        <th>Laboratorio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT o.*, m.nombre AS nombre_mascota, m.raza, u.nombre, u.apellido
                            FROM ordenes_laboratorio o
                            JOIN mascotas m ON o.id_mascota = m.id_mascota
                            JOIN usuarios u ON m.id_usuario = u.id_usuario
                            ORDER BY o.fecha_creacion DESC";

                    $stmt = $conexion->query($sql);
                    $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($ordenes as $orden) {
                        $nombreCompleto = $orden['nombre'] . " " . $orden['apellido'];
                        echo "<tr>";
                        echo "<td>{$orden['fecha_creacion']}</td>";
                        echo "<td>{$orden['nombre_mascota']} ({$orden['raza']})</td>";
                        echo "<td>{$nombreCompleto}</td>";
                        echo "<td>{$orden['pruebas']}</td>";
                        echo "<td>{$orden['laboratorio_destino']}</td>";
                        echo "<td>
                            <a href='verOrdenLaboratorio.php?id={$orden['id_orden']}' class='btn-action'><i class='fas fa-eye'></i> Ver</a>
                            <a href='ordenesLaboratorio.php?reordenar={$orden['id_orden']}' class='btn-action'><i class='fas fa-sync-alt'></i> Reordenar</a>
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- JS para cargar pacientes dinámicamente -->
<script>
document.getElementById("propietario").addEventListener("change", function () {
    const idUsuario = this.value;
    const pacienteSelect = document.getElementById("paciente");
    pacienteSelect.innerHTML = '<option value="">Cargando...</option>';

    fetch("buscarMascotasPorDueno.php?id_usuario=" + idUsuario)
        .then(response => response.json())
        .then(data => {
            pacienteSelect.innerHTML = '<option value="">Seleccione un paciente</option>';
            data.forEach(mascota => {
                pacienteSelect.innerHTML += `<option value="${mascota.id_mascota}">${mascota.nombre}</option>`;
            });
        });
});
</script>
</body>
</html>
