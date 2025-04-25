<?php
$hasil = "";
$nama_siswa = "";
$status_kelulusan = "";
$tampilkan_hasil = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "kelulusan";

    $conn = new mysqli($host, $user, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nisn = $conn->real_escape_string($_POST['nisn']);
    $query = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $nama_siswa = $data['nama'];
        $status_kelulusan = ucfirst($data['status']);
        $tampilkan_hasil = true;

        if ($status_kelulusan == "Lulus") {
            $hasil = "<div class='mt-4 p-4 bg-green-100 text-green-800 rounded-lg'>
                        <h3 class='text-lg font-bold'>$nama_siswa</h3>
                        <p class='text-xl font-semibold'>✅ LULUS</p>
                        <p>Selamat! Anda telah lulus. 🎉</p>
                        <a href='cetak.php?nisn=$nisn' class='mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600'>Cetak Bukti Kelulusan</a>
                      </div>";
        } else {
            $hasil = "<div class='mt-4 p-4 bg-red-100 text-red-800 rounded-lg'>
                        <h3 class='text-lg font-bold'>$nama_siswa</h3>
                        <p class='text-xl font-semibold'>❌ TIDAK LULUS</p>
                        <p>Maaf, Anda belum lulus. Tetap semangat! 💪</p>
                      </div>";
        }
    } else {
        $hasil = "<div class='mt-4 p-4 bg-red-100 text-red-800 rounded-lg'>NISN tidak ditemukan!</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kelulusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700">Cek Kelulusan Kelas 12</h2>
        <form method="POST" class="mt-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Masukkan NISN:</label>
            <input type="text" name="nisn" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-500 text-white mt-4 p-2 rounded-lg hover:bg-blue-600">Cek Kelulusan</button>
        </form>
        <?= $hasil; ?>
    </div>
</body>
</html>
