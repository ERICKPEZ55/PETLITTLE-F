<?php
require_once(__DIR__ . '/../configuracion/conexion.php');

// Cargar PhpSpreadsheet
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    $pdo = conexion();

    $sql = "SELECT u.id_usuario,
                   CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo,
                   u.correo,
                   u.telefono,
                   COUNT(m.id_mascota) AS cantidad_mascotas,
                   GROUP_CONCAT(m.nombre SEPARATOR ', ') AS nombres_mascotas
            FROM usuarios u
            LEFT JOIN mascotas m ON u.id_usuario = m.id_usuario
            GROUP BY u.id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre Completo');
    $sheet->setCellValue('C1', 'Correo');
    $sheet->setCellValue('D1', 'Teléfono');
    $sheet->setCellValue('E1', 'Cantidad Mascotas');
    $sheet->setCellValue('F1', 'Nombres Mascotas');

    // Estilo para encabezados
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

    $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

    // Contenido
    $row = 2;
    foreach ($usuarios as $usuario) {
        $sheet->setCellValue("A$row", $usuario['id_usuario']);
        $sheet->setCellValue("B$row", $usuario['nombre_completo']);
        $sheet->setCellValue("C$row", $usuario['correo']);
        $sheet->setCellValue("D$row", $usuario['telefono']);
        $sheet->setCellValue("E$row", $usuario['cantidad_mascotas']);
        $sheet->setCellValue("F$row", $usuario['nombres_mascotas']);
        $row++;
    }

    // Bordes para toda la tabla
    $sheet->getStyle("A1:F" . ($row - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Autowidth
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Descargar archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="usuarios_mascotas.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    echo 'Error al generar el archivo: ' . $e->getMessage();
}
