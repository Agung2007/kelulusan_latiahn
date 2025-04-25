<?php
require 'libs/fpdf/fpdf.php';

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['nisn'])) {
    $nisn = $conn->real_escape_string($_GET['nisn']);
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn' AND status = 'Lulus'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $nama_siswa = $data['nama'];
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Bukti Kelulusan', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Nama: $nama_siswa", 0, 1);
        $pdf->Cell(0, 10, "NISN: $nisn", 0, 1);
        $pdf->Cell(0, 10, "Status: LULUS ✅", 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Selamat atas kelulusan Anda!", 0, 1);
        $pdf->Output();
    } else {
        echo "Data tidak ditemukan atau siswa tidak lulus.";
    }
} else {
    echo "NISN tidak tersedia.";
}
?>
