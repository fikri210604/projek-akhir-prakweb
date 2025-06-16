<?php
session_start();
include '../includes/db.php';

// Validasi login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id = b.id WHERE p.id = $id AND p.users_id = {$_SESSION['user_id']}");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div style='text-align:center;padding:50px;font-family:sans-serif;'>
        <h2>Data peminjaman tidak ditemukan.</h2>
        <a href='dashboard_user.php' style='color:blue;'>Kembali ke Dashboard</a>
    </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tanggal Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h1 class="text-xl font-bold mb-4 text-center">Edit Tanggal Peminjaman</h1>
        <p class="text-center text-gray-600 mb-6">Judul: <strong><?= htmlspecialchars($data['judul']) ?></strong></p>
        <form action="update_tanggal.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium mb-1">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                       value="<?= $data['tanggal_pinjam'] ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="tanggal_kembali" class="block text-sm font-medium mb-1">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                       value="<?= $data['tanggal_kembali'] ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-between items-center">
                <a href="dashboard_user.php" class="text-blue-600 hover:underline">← Kembali</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
