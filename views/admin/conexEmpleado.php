<?php
 require_once(__DIR__ . '/../../models/empleado.php');
$pdo = conexion();
$empleadoModel = new Empleado($pdo);

$empleados = $empleadoModel->obtenerTodos();
?>

<table>
    <tr>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Contraseña</th>
        <th>Editar</th>
    </tr>
    <?php foreach ($empleados as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']) ?></td>
            <td><?= htmlspecialchars($emp['rol']) ?></td>
            <td><?= htmlspecialchars($emp['correo']) ?></td>
            <td><?= htmlspecialchars($emp['telefono']) ?></td>
            <td>••••••••</td>
            <td><button>Editar</button></td>
        </tr>
    <?php endforeach; ?>
</table>
