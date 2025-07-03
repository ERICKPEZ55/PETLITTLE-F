<?php
require_once '../../configuracion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mascota = $_POST['paciente']; // debe ser el id
    $tipo_muestra = $_POST['tipoMuestra'];
    $pruebas = $_POST['pruebas'];
    $laboratorio = $_POST['laboratorio'];
    $urgencia = $_POST['urgencia'];
    $notas = $_POST['notasClinicas'];
    $fecha_creacion = date('Y-m-d');

    try {
        $conexion = new Conexion();
        $pdo = $conexion->conectar();

        $stmt = $pdo->prepare("INSERT INTO ordenes_laboratorio (id_mascota, tipo_muestra, pruebas, laboratorio, urgencia, notas_clinicas, fecha_creacion)
                               VALUES (:id_mascota, :tipo_muestra, :pruebas, :laboratorio, :urgencia, :notas, :fecha)");
        $stmt->execute([
            'id_mascota' => $id_mascota,
            'tipo_muestra' => $tipo_muestra,
            'pruebas' => $pruebas,
            'laboratorio' => $laboratorio,
            'urgencia' => $urgencia,
            'notas' => $notas,
            'fecha' => $fecha_creacion
        ]);

        header('Location: ordenesLaboratorio.php?mensaje=exito');
        exit();
    } catch (PDOException $e) {
        echo "Error al insertar la orden: " . $e->getMessage();
    }
} else {
    echo "Método de solicitud no válido.";
}