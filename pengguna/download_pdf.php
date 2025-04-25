<?php
require('fpdf/fpdf.php');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed");
}

if (isset($_GET['nisn'])) {
    $nisn = $conn->real_escape_string($_GET['nisn']);
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'SURAT KELULUSAN', 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Nama: " . $data['nama'], 0, 1);
        $pdf->Cell(0, 10, "NISN: " . $data['nisn'], 0, 1);
        $pdf->Cell(0, 10, "Status Kelulusan: " . ucfirst($data['status']), 0, 1);
        
        if ($data['status'] == 'lulus') {
            $pdf->Ln(10);
            $pdf->Cell(0, 10, "Selamat! Anda telah lulus.", 0, 1);
        } else {
            $pdf->Ln(10);
            $pdf->Cell(0, 10, "Mohon maaf, Anda belum lulus.", 0, 1);
        }
        
        $pdf->Output('D', 'Surat_Kelulusan.pdf');
    } else {
        echo "NISN tidak ditemukan";
    }
} else {
    echo "Masukkan NISN";
}

$conn->close();
?>
