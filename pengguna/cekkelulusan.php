<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nisn']) || empty($data['nisn'])) {
    echo json_encode(["status" => "error", "message" => "Masukkan NISN"]);
    exit();
}

$nisn = $conn->real_escape_string($data['nisn']);
$query = "SELECT nama, status FROM siswa WHERE nisn = '$nisn'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "status" => "success", 
        "data" => [
            "nama" => $row['nama'],
            "kelulusan" => $row['status']
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "NISN tidak ditemukan"]);
}

$conn->close();
?>
