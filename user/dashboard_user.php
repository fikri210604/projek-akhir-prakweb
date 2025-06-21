<?php
session_start();
include '../includes/db.php';
include 'asset/navbar.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$queryPeminjaman = mysqli_query($conn, "
    SELECT p.*, b.judul, b.foto, DATEDIFF(p.tanggal_kembali, CURDATE()) AS sisa_hari 
    FROM peminjaman p 
    JOIN buku b ON p.buku_id = b.id 
    WHERE p.users_id = $user_id AND p.status = 'dipinjam'
");
$dataPeminjaman = mysqli_fetch_all($queryPeminjaman, MYSQLI_ASSOC);

// Query kategori
$queryKategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori");

// Perbaiki query buku - ambil semua buku yang tersedia (tidak peduli status dipinjam)
$queryBuku = mysqli_query($conn, "
    SELECT b.id, b.judul, b.kategori_id, k.nama_kategori 
    FROM buku b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    WHERE b.jumlah >= 0
    ORDER BY b.judul ASC
");

// Debug: cek berapa buku yang diambil
$totalBuku = mysqli_num_rows($queryBuku);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rounded-xl-custom {
            border-radius: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="container mx-auto px-10 py-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold mt-10 mb-5">
                📚 Selamat Datang <span><?= htmlspecialchars($_SESSION['nama'] ?? 'penyewa') ?></span> di SIMPENKU!
            </h1>
            <p class="text-gray-600">Pantau dan pinjam buku dengan mudah</p>
            <!-- Debug info -->
            <p class="text-xs text-gray-400">Total buku tersedia: <?= $totalBuku ?></p>
        </div>

        <!-- Grid Utama -->
        <div class="grid grid-cols-2 lg:grid-cols-2 gap-8">
            <!-- Kiri: Kalender dan Daftar Buku -->
            <div>
                <!-- Kalender -->
                <div class="bg-white shadow rounded-xl-custom p-6">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4 text-left">Cek Tanggal Pinjam-mu!</h2>
                    <p id="calendar-title" class="text-gray-600 mb-4 text-left"></p>
                    <div class="grid grid-cols-7 text-center text-sm font-semibold text-gray-700 gap-1">
                        <div>Min</div>
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                    </div>
                    <div id="dates" class="grid grid-cols-7 text-center text-sm gap-1 mt-2"></div>
                </div>

                <!-- Buku Dipinjam -->
                <div class="mt-6 bg-white shadow rounded-xl-custom p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-4">Daftar Buku yang Dipinjam</h3>
                    <?php if (count($dataPeminjaman) === 0): ?>
                        <p class="text-gray-600">Kamu belum meminjam buku apapun.</p>
                    <?php endif; ?>

                    <!-- Card Buku -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
                        <?php foreach ($dataPeminjaman as $row): ?>
                            <div
                                class="relative group bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
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
                </div>
            </div>

            <!-- Kanan: Notifikasi dan Form -->
            <div class="flex flex-col gap-6">
                <!-- Notification Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-xl-custom shadow">
                    <h2 class="text-lg font-semibold mb-2">📘 Buku Dipinjam</h2>
                    <p class="text-sm mb-1">Kamu telah meminjam
                        <strong><?= mysqli_num_rows($queryPeminjaman) ?></strong> buku
                    </p>
                    <p class="text-sm">Cek daftar buku dan pastikan kamu mengembalikannya tepat waktu!</p>
                </div>

                <!-- Form Peminjaman Buku -->
                <div class="bg-white p-6 rounded-xl-custom shadow">
                    <h2 class="text-xl font-semibold mb-6 text-blue-600">📥 Form Peminjaman Buku</h2>
                    <form action="../includes/proses_pinjam.php" method="POST" class="space-y-8">
                        <div>
                            <label class="block mb-3 font-medium text-sm">Pilih Kategori</label>
                            <select name="kategori_id" id="kategori_select" class="w-full border border-gray-300 rounded px-3 py-2">
                                <option value="">-- Pilih Kategori --</option>
                                <?php 
                                mysqli_data_seek($queryKategori, 0);
                                while ($kategori = mysqli_fetch_assoc($queryKategori)): ?>
                                    <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-3 font-medium text-sm">Pilih Buku</label>
                            <select name="buku_id" id="buku_select" required class="w-full border border-gray-300 rounded px-3 py-2">
                                <option value="">-- Pilih Buku --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-3 font-medium text-sm">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" min="<?= date('Y-m-d'); ?>" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block mb-3 font-medium text-sm">Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" min="<?= date('Y-m-d'); ?>" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Pinjam
                            Buku</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Kalender JS
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const datesContainer = document.getElementById('dates');
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('calendar-title').innerText = `${monthNames[month]} ${year}`;
        for (let i = 0; i < firstDay; i++) {
            datesContainer.innerHTML += '<div></div>';
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const isToday = d === today.getDate();
            datesContainer.innerHTML += `<div class="${isToday ? 'bg-blue-600 text-white font-bold rounded-full' : 'text-gray-700'} py-1">${d}</div>`;
        }

        // Data buku untuk JavaScript
        const bukuData = [
            <?php 
            mysqli_data_seek($queryBuku, 0);
            $bukuArray = [];
            while ($buku = mysqli_fetch_assoc($queryBuku)) {
                $kategori_id = $buku['kategori_id'] ? $buku['kategori_id'] : 'null';
                $nama_kategori = $buku['nama_kategori'] ? addslashes($buku['nama_kategori']) : 'Tidak ada kategori';
                $bukuArray[] = "{
                    id: {$buku['id']}, 
                    judul: '" . addslashes($buku['judul']) . "', 
                    kategori_id: {$kategori_id}, 
                    nama_kategori: '{$nama_kategori}'
                }";
            }
            echo implode(',', $bukuArray);
            ?>
        ];

        console.log('Total buku loaded:', bukuData.length);
        console.log('Data buku:', bukuData);

        const kategoriSelect = document.getElementById('kategori_select');
        const bukuSelect = document.getElementById('buku_select');

        // Function untuk populate buku berdasarkan kategori
        function populateBuku(kategoriId = null) {
            console.log('populateBuku dipanggil dengan kategoriId:', kategoriId);
            
            // Reset buku select
            bukuSelect.innerHTML = '<option value="">-- Pilih Buku --</option>';
            
            let filteredBuku;
            if (kategoriId && kategoriId !== '') {
                // Filter buku berdasarkan kategori yang dipilih
                filteredBuku = bukuData.filter(buku => {
                    const match = String(buku.kategori_id) === String(kategoriId);
                    return match;
                });
                console.log(`Buku dengan kategori ${kategoriId}:`, filteredBuku);
            } else {
                // Tampilkan semua buku jika tidak ada kategori yang dipilih
                filteredBuku = bukuData;
                console.log('Menampilkan semua buku:', filteredBuku.length, 'buku');
            }
            
            // Tambahkan option buku ke select - HANYA JUDUL TANPA KATEGORI
            filteredBuku.forEach(buku => {
                const option = document.createElement('option');
                option.value = buku.id;
                option.textContent = buku.judul; // Hanya judul, tanpa kategori dalam kurung
                option.setAttribute('data-kategori', buku.kategori_id);
                bukuSelect.appendChild(option);
            });
            
            console.log(`Total ${filteredBuku.length} buku ditambahkan ke dropdown`);
        }

        // Event listener untuk kategori
        kategoriSelect.addEventListener('change', function() {
            const selectedKategori = this.value;
            console.log('Kategori dipilih:', selectedKategori);
            
            // Reset pilihan buku
            bukuSelect.value = '';
            
            // Populate buku sesuai kategori
            populateBuku(selectedKategori);
        });

        // Event listener untuk buku
        bukuSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.getAttribute('data-kategori')) {
                const kategoriId = selectedOption.getAttribute('data-kategori');
                console.log('Buku dipilih, kategori_id:', kategoriId);
                
                // Set kategori otomatis jika belum dipilih
                if (!kategoriSelect.value || kategoriSelect.value !== kategoriId) {
                    kategoriSelect.value = kategoriId;
                    console.log('Kategori diset ke:', kategoriId);
                }
            }
        });

        // Inisialisasi: tampilkan semua buku saat pertama kali load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, menginisialisasi dropdown buku...');
            populateBuku();
        });

        // Validasi tanggal
        document.getElementById('tanggal_pinjam').addEventListener('change', function() {
            const tanggalPinjam = new Date(this.value);
            const tanggalKembali = document.getElementById('tanggal_kembali');
            
            const minKembali = new Date(tanggalPinjam);
            minKembali.setDate(minKembali.getDate() + 1);
            
            tanggalKembali.min = minKembali.toISOString().split('T')[0];
            
            if (tanggalKembali.value && new Date(tanggalKembali.value) <= tanggalPinjam) {
                tanggalKembali.value = '';
            }
        });
    </script>

</body>

</html>
