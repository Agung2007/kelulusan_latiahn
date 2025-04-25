<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses pendaftaran admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Cek apakah username sudah ada
    $check_query = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $check_query->bind_param("s", $username);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location.href = 'register.php';</script>";
        exit();
    }
    
    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    
    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi.'); window.location.href = 'register.php';</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-2xl font-bold mb-6 text-center">Daftar Admin</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">Daftar</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>
