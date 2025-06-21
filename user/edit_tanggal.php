<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query(
    $conn,
    "SELECT p.*, b.judul 
     FROM peminjaman p 
     JOIN buku b ON p.buku_id = b.id 
     WHERE p.id = $id AND p.users_id = {$_SESSION['user_id']}"
);
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<div class='text-center p-10 font-sans'>
            <h2>Data peminjaman tidak ditemukan.</h2>
            <a href='dashboard_user.php' class='text-blue-600'>← Kembali ke Dashboard</a>
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
<body class="bg-gray-100 min-h-screen">

    <?php include 'asset/navbar.php'; ?>

    <!-- Form container -->
    <div class="pt-24 flex justify-center items-start px-4">
        <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
            <h1 class="text-xl font-bold mb-2 text-center">Edit Tanggal Peminjaman</h1>
            <p class="text-center text-gray-600 mb-6">Judul: <strong><?= htmlspecialchars($data['judul']) ?></strong></p>

            <form action="<?= base_url('includes/update_peminjaman.php') ?>" method="POST" class="space-y-4">
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

                <div class="flex justify-between items-center pt-2">
                    <a href="dashboard_user.php" class="text-blue-600 hover:underline">&larr; Kembali</a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
