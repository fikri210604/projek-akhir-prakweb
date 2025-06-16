<?php
session_start();

include '../includes/db.php';
include 'asset/navbar.php';
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data peminjaman
$queryPeminjaman = mysqli_query($conn, "
    SELECT p.*, b.judul, b.foto, DATEDIFF(p.tanggal_kembali, CURDATE()) AS sisa_hari 
    FROM peminjaman p 
    JOIN buku b ON p.buku_id = b.id 
WHERE p.users_id = $user_id AND p.status = 'dipinjam'
");

$queryBuku = mysqli_query($conn, "SELECT * FROM buku");
$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");
$dataPeminjaman = mysqli_fetch_all($queryPeminjaman, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-1 mt-10">📚 Dashboard User</h1>
            <p class="text-gray-600">Pantau dan pinjam buku dengan mudah</p>
        </div>

        <!-- Card Jumlah Buku -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-600 text-white p-6 rounded-xl shadow-md">
                <div class="flex items-center">
                    <div class="text-4xl mr-4">📖</div>
                    <div>
                        <p class="text-sm uppercase tracking-wide">Buku Dipinjam</p>
                        <p class="text-3xl font-bold"><?= mysqli_num_rows($queryPeminjaman) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-4">Buku yang Dipinjam</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($dataPeminjaman as $row): ?>
                <div class="relative group bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <a href="detail_buku.php?id=<?= $row['buku_id'] ?>">
                        <img src="../uploads/buku/<?= $row['foto'] ?>" alt="<?= $row['judul'] ?>"
                            class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="detail_buku.php?id=<?= $row['buku_id'] ?>" class="hover:underline">
                                <?= htmlspecialchars($row['judul']) ?>
                            </a>
                        </h3>
                        <span
                            class="inline-block px-3 py-1 text-sm rounded-full <?= $row['sisa_hari'] <= 3 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' ?>">
                            Sisa <?= $row['sisa_hari'] ?> hari
                        </span>
                    </div>
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="edit_tanggal.php?id=<?= $row['id'] ?>"
                            class="bg-yellow-500 text-white px-3 py-1 text-sm rounded hover:bg-yellow-600 shadow">
                            Edit Tanggal
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- Form Peminjaman Buku -->
        <div class="mt-12">
            <h2 class="text-xl font-semibold mb-4">Form Peminjaman Buku</h2>
            <form action="../includes/proses_pinjam.php" method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium mb-1">Pilih Kategori</label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while ($kategori = mysqli_fetch_assoc($queryKategori)): ?>
                                <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="buku_id" class="block text-sm font-medium mb-1">Pilih Buku</label>
                        <select name="buku_id" id="buku_id"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Buku --</option>
                            <?php while ($buku = mysqli_fetch_assoc($queryBuku)): ?>
                                <option value="<?= $buku['id'] ?>"><?= $buku['judul'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-medium mb-1">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-medium mb-1">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Pinjam Buku
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById("kategori_id").addEventListener("change", function () {
                let kategoriId = this.value;
                let bukuSelect = document.getElementById("buku_id");

                fetch("get_buku_by_kategori.php?kategori_id=" + kategoriId)
                    .then(response => response.json())
                    .then(data => {
                        bukuSelect.innerHTML = '<option value="">-- Pilih Buku --</option>';
                        data.forEach(buku => {
                            let option = document.createElement("option");
                            option.value = buku.id;
                            option.textContent = buku.judul;
                            bukuSelect.appendChild(option);
                        });
                    });
            });
        </script>
</body>

</html>