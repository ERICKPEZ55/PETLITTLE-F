<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../configuracion/conexion.php');
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    $pdo = conexion();

    $sql = "SELECT m.nombre AS nombre_mascota, m.raza, m.sexo,
                   CONCAT(u.nombre, ' ', u.apellido) AS nombre_dueno
            FROM mascotas m
            INNER JOIN usuarios u ON m.id_usuario = u.id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $mascotas = $stmt->fetchAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Raza');
    $sheet->setCellValue('C1', 'Género');
    $sheet->setCellValue('D1', 'Dueño');

    // Estilos para encabezado
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '6EACDA']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
    ];

    $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

    // Contenido
    $fila = 2;
    foreach ($mascotas as $row) {
        $sheet->setCellValue("A$fila", $row['nombre_mascota']);
        $sheet->setCellValue("B$fila", $row['raza']);
        $sheet->setCellValue("C$fila", $row['sexo']);
        $sheet->setCellValue("D$fila", $row['nombre_dueno']);
        $fila++;
    }

    // Bordes para toda la tabla
    $sheet->getStyle("A1:D" . ($fila - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Autowidth para columnas
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Descargar archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="mascotas_registradas.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    echo 'Error al generar el archivo: ' . $e->getMessage();
}
