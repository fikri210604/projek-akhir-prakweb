<?php
include 'includes/db.php';

$search = $_GET['search'] ?? '';
$kategori_id = $_GET['kategori'] ?? '';
$where = [];

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where[] = "judul LIKE '%$search%'";
}
if (!empty($kategori_id)) {
    $kategori_id = (int) $kategori_id;
    $where[] = "kategori_id = $kategori_id";
}
$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
$buku = mysqli_query($conn, "SELECT * FROM buku $where_sql");
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>SIMPENKU | Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html,
    body {
      height: 100%;
      overflow: hidden;
    }

    @media (max-width: 1024px) {

      html,
      body {
        overflow: auto;
      }
    }
  </style>
</head>

<body class="bg-blue-50 min-h-screen flex flex-col h-screen overflow-hidden">

  <!-- Navbar -->
  <nav class="bg-blue-50 shadow-none py-3 px-8 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <img src="https://img.icons8.com/color/48/000000/books.png" class="w-9" alt="Logo">
      <span class="text-xl font-bold text-blue-700">SIMPENKU</span>
    </div>
    <div>
      <a href="login.php"
        class="bg-blue-500 text-white px-4 py-2 rounded-xl mr-2 hover:bg-blue-600 transition">Login</a>
      <a href="register.php"
        class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl border border-blue-500 hover:bg-blue-200 transition">Register</a>
    </div>
  </nav>

  <main class="flex-1 flex flex-col justify-between">
    <div class="relative flex flex-col items-center pt-7 pb-4 bg-blue-50 w-full">
      <div class="flex items-center w-full max-w-5xl mx-auto relative">
        <!-- Gambar kiri -->
        <div class="flex-shrink-0 flex flex-col justify-center items-center h-full mr-3">
          <img src="discover1.png" class="w-24 md:w-28 -mt-24" alt="Gambar kiri" />
        </div>
        <div class="flex-1 flex flex-col items-center">
          <h1 class="text-3xl md:text-4xl font-extrabold text-center text-blue-900 mb-0">
            Selamat datang di <span class="text-blue-700">SIMPENKU</span>
          </h1>
          <span class="block text-black text-lg md:text-2xl font-medium mt-3 mb-0 text-center w-full">
            Temukan buku bacaan
          </span>
          <span class="block text-black text-lg md:text-2xl font-medium mt-3 mb-0 text-center w-full">
            favoritmu!
          </span>
          <form method="get" class="flex w-full max-w-lg mx-auto mb-2 mt-6">
            <input name="search" value="<?= htmlspecialchars($search) ?>"
              class="flex-1 rounded-l-full border border-blue-300 px-4 py-2 focus:outline-none" type="text"
              placeholder="Cari Buku">
            <button type="submit"
              class="rounded-r-full bg-orange-400 text-white px-6 py-2 font-bold hover:bg-orange-500 transition">
              Cari
            </button>
          </form>
          <!-- Kategori -->
          <div class="flex flex-wrap gap-2 justify-center mb-1">
            <a href="?"
              class="px-3 py-1 rounded-full bg-blue-200 hover:bg-blue-400 text-blue-900 text-xs font-medium">Semua</a>
            <?php
            mysqli_data_seek($kategori, 0);
            while ($kat = mysqli_fetch_assoc($kategori)): ?>
              <a href="?kategori=<?= $kat['id'] ?>"
                class="px-3 py-1 rounded-full bg-blue-100 hover:bg-blue-400 text-blue-900 text-xs font-medium"><?= htmlspecialchars($kat['nama_kategori']) ?></a>
            <?php endwhile; ?>
          </div>
        </div>
        <!-- Gambar kanan -->
        <div class="flex-shrink-0 flex flex-col justify-center items-center h-full ml-3">
          <img src="uploads/gambar00.png" class="w-24 md:w-28 mt-12" alt="Gambar kanan" />
        </div>
      </div>
    </div>
    
    <!-- Daftar Buku -->
    <section class="flex flex-col justify-end pb-0 mb-0 -mt-14 ">
      <div class="max-w-7xl mx-auto px-4 pt-0 pb-0 w-full flex flex-col">

        <div class="flex items-center mb-1">
          <div class="w-24 md:w-24 lg:w-28"></div>
          <h2 class="font-bold text-xl text-left pl-6 -mt-14 ">
            <?= ($search === "") ? "Populer" : "Hasil Pencarian" ?>
          </h2>
        </div>
        <div class="flex items-end">
          <div class="w-24 md:w-24 lg:w-28 flex-shrink-0"></div>
          <div class="flex-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-0">
              <?php while ($row = mysqli_fetch_assoc($buku)): ?>
                <div class="flex flex-col items-center">
                  <img src="uploads/buku/<?= !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'default.png' ?>"
                    class="h-40 w-32 object-cover rounded-xl mb-2 border border-blue-200 shadow" alt="cover">
                  <span class="font-extrabold text-sm text-black uppercase tracking-wide mb-1 text-center">
                    <?= strtoupper(htmlspecialchars($row['judul'])) ?>
                  </span>
                  <span class="text-xs text-gray-600 text-center mb-1"><?= htmlspecialchars($row['penulis']) ?></span>
                </div>
              <?php endwhile; ?>
              <?php if (mysqli_num_rows($buku) == 0): ?>
                <p class="col-span-5 text-center text-gray-500">Tidak ada buku ditemukan.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="w-full text-center py-3 text-blue-700 text-xs mt-0">
    &copy; <?= date('Y') ?> SIMPENKU - Semua hak cipta dilindungi
  </footer>
</body>

</html>