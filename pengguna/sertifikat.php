<?php
require('../libs/fpdf/fpdf.php');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";

// Buat koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Buat objek FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Tambahkan logo sekolah
        $pdf->Image('../assets/logo.png', 10, 10, 30);
        $pdf->Cell(190, 10, 'SERTIFIKAT KELULUSAN', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, 'Diberikan kepada:', 0, 1, 'C');
        
        // Nama siswa
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, strtoupper($data['nama']), 0, 1, 'C');

        // NISN
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, "NISN: " . $data['nisn'], 0, 1, 'C');

        // Status kelulusan
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, 'DINYATAKAN: ' . strtoupper($data['status']), 0, 1, 'C');

        // Tanda tangan kepala sekolah
        $pdf->Ln(20);
        $pdf->Cell(190, 10, 'Kepala Sekolah', 0, 1, 'R');
        $pdf->Ln(20);
        $pdf->Cell(190, 10, '_________________________', 0, 1, 'R');

        // Output PDF
        $pdf->Output('D', 'Sertifikat_' . $data['nisn'] . '.pdf');
    } else {
        echo "NISN tidak ditemukan.";
    }
} else {
    echo "Masukkan NISN.";
}
?>
