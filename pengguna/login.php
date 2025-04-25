<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Password dalam bentuk plaintext (belum dienkripsi)

    // Query untuk mengambil user berdasarkan username
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Login gagal! Periksa username dan password.');</script>";
        }
    } else {
        echo "<script>alert('Login gagal! Periksa username dan password.');</script>";
    }
    
    // Tutup statement
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Login Admin</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>
    </div>
</body>
</html>
