<?php
require 'config.php'; // Koneksi database
require 'libs/fpdf/fpdf.php'; // Library FPDF
require 'vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$format = isset($_GET['format']) ? $_GET['format'] : '';

if ($format == 'pdf') {
    exportPDF();
} elseif ($format == 'excel') {
    exportExcel();
} else {
    echo "Format tidak valid!";
}

function exportPDF() {
    global $conn;

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'Daftar Siswa', 1, 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'ID', 1);
    $pdf->Cell(60, 10, 'Nama', 1);
    $pdf->Cell(30, 10, 'NISN', 1);
    $pdf->Cell(40, 10, 'Status', 1);
    $pdf->Ln();

    $result = $conn->query("SELECT * FROM siswa");
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(10, 10, $row['id'], 1);
        $pdf->Cell(60, 10, $row['nama'], 1);
        $pdf->Cell(30, 10, $row['nisn'], 1);
        $pdf->Cell(40, 10, ucfirst($row['status']), 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'Daftar_Siswa.pdf');
}

function exportExcel() {
    global $conn;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nama');
    $sheet->setCellValue('C1', 'NISN');
    $sheet->setCellValue('D1', 'Status');

    $result = $conn->query("SELECT * FROM siswa");
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['id']);
        $sheet->setCellValue('B' . $rowIndex, $row['nama']);
        $sheet->setCellValue('C' . $rowIndex, $row['nisn']);
        $sheet->setCellValue('D' . $rowIndex, ucfirst($row['status']));
        $rowIndex++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'Daftar_Siswa.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $writer->save('php://output');
}
?>
