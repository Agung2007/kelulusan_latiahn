<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kelulusan";
$conn = new mysqli($host, $user, $password, $dbname);

if (isset($_POST['add'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $status = $_POST['status'];
    
    $conn->query("INSERT INTO siswa (nisn, nama, kelas, status) VALUES ('$nisn', '$nama', '$kelas', '$status')");
    header("Location: dashboard.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM siswa WHERE id=$id");
    header("Location: dashboard.php");
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM siswa WHERE nisn LIKE '%$search%' OR nama LIKE '%$search%'");
} else {
    $result = $conn->query("SELECT * FROM siswa");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-5 bg-gray-100">
    <h2 class="text-xl font-bold mb-4">Manajemen Siswa</h2>
    
    <form method="POST" class="mb-4 bg-white p-4 shadow rounded flex flex-wrap gap-2">
        <input type="text" name="nisn" placeholder="Nisn" required class="border p-2 flex-1"> 
        <input type="text" name="nama" placeholder="Nama" required class="border p-2 flex-1"> 
        <input type="text" name="kelas" placeholder="Kelas" required class="border p-2 flex-1"> 
        <select name="status" class="border p-2 flex-1">
            <option value="Lulus">Lulus</option>
            <option value="Tidak Lulus">Tidak Lulus</option>
        </select>
        <button type="submit" name="add" class="bg-blue-500 text-white p-2 rounded">Tambah</button>
    </form>
    
    <form method="GET" class="mb-4 bg-white p-4 shadow rounded flex gap-2">
        <input type="text" name="search" placeholder="Cari NISN atau Nama" value="<?php echo $search; ?>" class="border p-2 flex-1">
        <button type="submit" class="bg-green-500 text-white p-2 rounded">Cari</button>
    </form>
    
    <div class="overflow-x-auto">
        <table class="bg-white w-full shadow rounded text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3 border">NISN</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Kelas</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="p-3 border"><?php echo $row['nisn']; ?></td>
                    <td class="p-3 border"><?php echo $row['nama']; ?></td>
                    <td class="p-3 border"><?php echo $row['kelas']; ?></td>
                    <td class="p-3 border"><?php echo $row['status']; ?></td>
                    <td class="p-3 border text-center">
                        <a href="dashboard.php?delete=<?php echo $row['id']; ?>" class="text-red-500">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
