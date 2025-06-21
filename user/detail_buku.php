<?php
session_start();
include '../includes/db.php';
include 'asset/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$buku_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan detail buku
$query = mysqli_query($conn, "
    SELECT b.*, k.nama_kategori, p.nama_penulis, p.bio as bio_penulis, p.tanggal_lahir, p.kebangsaan, p.jenis_kelamin
    FROM buku b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    LEFT JOIN penulis p ON b.penulis_id = p.id 
    WHERE b.id = $buku_id
");

$buku = mysqli_fetch_assoc($query);

if (!$buku) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-50 pt-20'>
            <div class='text-center'>
                <h2 class='text-2xl font-bold text-gray-800 mb-4'>Buku tidak ditemukan</h2>
                <a href='dashboard_user.php' class='text-blue-600 hover:underline'>← Kembali ke Dashboard</a>
            </div>
          </div>";
    exit;
}

// Cek apakah user sudah meminjam buku ini
$cekPinjam = mysqli_query($conn, "
    SELECT * FROM peminjaman 
    WHERE users_id = $user_id AND buku_id = $buku_id AND status = 'dipinjam'
");
$sudahPinjam = mysqli_num_rows($cekPinjam) > 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?= htmlspecialchars($buku['judul']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Container utama dengan padding top untuk navbar -->
    <div class="pt-20 pb-8 px-4 sm:px-6 lg:px-8">
        <!-- Card utama -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row">
                <!-- Kolom gambar buku -->
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center p-4 lg:w-auto">
                    <img src="../uploads/buku/<?= htmlspecialchars($buku['foto']) ?>" 
                         alt="<?= htmlspecialchars($buku['judul']) ?>"
                         class="w-80 h-[475px] object-contain rounded-xl shadow-lg">
                </div>

                <!-- Kolom informasi buku -->
                <div class="flex-1 p-8">
                    <!-- Header buku -->
                    <div class="mb-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-3">
                            <?= htmlspecialchars($buku['judul']) ?>
                        </h1>
                        <p class="text-xl text-blue-600 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            oleh <?= htmlspecialchars($buku['nama_penulis'] ?? 'Tidak diketahui') ?>
                        </p>
                    </div>

                    <!-- Grid informasi -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                        <!-- Informasi Buku -->
                        <div>
                            <h3 class="text-2xl font-bold text-blue-600 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                                </svg>
                                Informasi Buku
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                                    <span class="text-gray-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Penerbit
                                    </span>
                                    <span class="font-bold text-gray-800"><?= htmlspecialchars($buku['penerbit']) ?></span>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-green-500">
                                    <span class="text-gray-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tahun Terbit
                                    </span>
                                    <span class="font-bold text-gray-800"><?= $buku['tahun_terbit'] ?></span>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                                    <span class="text-gray-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        Stok Tersedia
                                    </span>
                                    <span class="font-bold text-xl <?= $buku['jumlah'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $buku['jumlah'] ?> buku
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tentang Penulis -->
                        <div>
                            <h3 class="text-2xl font-bold text-green-600 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                </svg>
                                Tentang Penulis
                            </h3>
                            
                            <?php if ($buku['nama_penulis']): ?>
                                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                                    <h4 class="font-bold text-xl text-gray-900 mb-3"><?= htmlspecialchars($buku['nama_penulis']) ?></h4>
                                    
                                    <?php if ($buku['bio_penulis']): ?>
                                        <p class="text-gray-700 mb-4"><?= htmlspecialchars($buku['bio_penulis']) ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="space-y-2 text-sm">
                                        <?php if ($buku['tanggal_lahir']): ?>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 01-1 1H8a1 1 0 110-2h4a1 1 0 011 1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-600">Lahir: </span>
                                                <span class="font-medium ml-1"><?= date('d F Y', strtotime($buku['tanggal_lahir'])) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($buku['kebangsaan']): ?>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-600">Kebangsaan: </span>
                                                <span class="font-medium ml-1"><?= htmlspecialchars($buku['kebangsaan']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($buku['jenis_kelamin']): ?>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 9a1 1 0 000 2h4a1 1 0 100-2H8z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-600">Jenis Kelamin: </span>
                                                <span class="font-medium ml-1"><?= htmlspecialchars($buku['jenis_kelamin']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-6 bg-gray-100 rounded-lg text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-gray-500">Informasi penulis tidak tersedia</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tombol kembali -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <form action="dashboard_user.php" method="get">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span class="inline-flex items-center font-medium">
                                    Kembali ke Dashboard
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>