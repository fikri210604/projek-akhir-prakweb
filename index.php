<?php
include 'includes/db.php';

$search = $_GET['search'] ?? '';
$kategori_id = $_GET['kategori'] ?? '';
$penulis_id = $_GET['penulis'] ?? '';
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
$penulis = mysqli_query($conn, "SELECT * FROM penulis");
$buku = mysqli_query($conn, "
    SELECT buku.*, penulis.nama_penulis, kategori.nama_kategori 
    FROM buku 
    LEFT JOIN penulis ON buku.penulis_id = penulis.id 
    LEFT JOIN kategori ON buku.kategori_id = kategori.id 
    $where_sql
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>SIMPENKU | Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 flex flex-col min-h-screen">

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

  <!-- Hero Section -->
  <section class="bg-blue-50 py-8">
    <div class="max-w-6xl mx-auto flex items-center justify-between px-4">
      <img src="discover1.png" class="w-24 md:w-28 hidden md:block -mt-10" alt="Gambar kiri">
      <div class="text-center flex-1">
        <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900">
          Selamat datang di <span class="text-blue-700">SIMPENKU</span>
        </h1>
        <p class="text-lg md:text-2xl font-medium text-black mt-2">Temukan buku bacaan favoritmu!</p>
      </div>
      <img src="uploads/gambar00.png" class="w-24 md:w-28 hidden md:block mt-10" alt="Gambar kanan">
    </div>
  </section>

  <!-- Pencarian dan Kategori -->
  <section class="bg-blue-50 py-4">
    <div class="max-w-4xl mx-auto px-4">
      <!-- Form Pencarian -->
      <form method="get" class="flex w-full mb-4">
        <input name="search" value="<?= htmlspecialchars($search) ?>"
          class="flex-1 rounded-l-full border border-blue-300 px-4 py-2 focus:outline-none" type="text"
          placeholder="Cari Buku">
        <button type="submit"
          class="rounded-r-full bg-orange-400 text-white px-6 py-2 font-bold hover:bg-orange-500 transition">
          Cari
        </button>
      </form>

      <!-- Kategori -->
      <div class="flex flex-wrap gap-2 justify-center mb-2">
        <a href="?"
          class="px-3 py-1 rounded-full bg-blue-200 hover:bg-blue-400 text-blue-900 text-xs font-medium">Semua</a>
        <?php
        mysqli_data_seek($kategori, 0);
        while ($kat = mysqli_fetch_assoc($kategori)): ?>
          <a href="?kategori=<?= $kat['id'] ?>"
            class="px-3 py-1 rounded-full bg-blue-100 hover:bg-blue-400 text-blue-900 text-xs font-medium">
            <?= htmlspecialchars($kat['nama_kategori']) ?>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- Daftar Kategori -->
  <section class= "py-8 ">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-2xl font-bold text-blue-800 mb-6">Kategori Buku</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php
        mysqli_data_seek($kategori, 0); // reset pointer
        while ($kat = mysqli_fetch_assoc($kategori)): ?>
          <a href="?kategori=<?= $kat['id'] ?>"
            class="flex flex-col items-center bg-white border rounded-lg p-3 hover:shadow transition">
            <img
              src="uploads/kategori/<?= !empty($kat['foto_kategori']) ? htmlspecialchars($kat['foto_kategori']) : 'default_kategori.png' ?>"
              alt="<?= htmlspecialchars($kat['nama_kategori']) ?>"
              class="w-40 h-32 object-cover rounded-lg mb-2 border border-blue-300">
            <span class="text-sm font-semibold text-center text-blue-800">
              <?= htmlspecialchars($kat['nama_kategori']) ?>
            </span>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- Daftar Buku -->
  <section class= "py-8 ">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-2xl font-bold text-blue-800 mb-6">Daftar Penulis</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php
        mysqli_data_seek($penulis, 0); // reset pointer
        while ($kat = mysqli_fetch_assoc($penulis)): ?>
          <a href="?kategori=<?= $kat['id'] ?>"
            class="flex flex-col items-center bg-white border rounded-lg p-3 hover:shadow transition">
            <img
              src="uploads/penulis/<?= !empty($kat['foto']) ? htmlspecialchars($kat['foto']) : 'default_penulis.png' ?>"
              alt="<?= htmlspecialchars($kat['nama_penulis']) ?>"
              class="w-40 h-32 object-cover rounded-lg mb-2 border border-blue-300">
            <span class="text-sm font-semibold text-center text-blue-800">
              <?= htmlspecialchars($kat['nama_penulis']) ?>
            </span>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- Daftar Buku -->
  <section class="flex-1 py-6">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-2xl font-bold text-blue-800 mb-4"><?= $search ? "Hasil Pencarian" : "Buku Populer" ?></h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php
        if (mysqli_num_rows($buku) > 0):
          while ($row = mysqli_fetch_assoc($buku)): ?>
            <div class="bg-white shadow-md rounded-lg p-3 flex flex-col items-center border border-blue-100">
              <img src="uploads/buku/<?= !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'default.png' ?>"
                class="h-40 w-32 object-cover rounded mb-2" alt="cover">
              <span class="font-bold text-sm text-center text-black mb-1">
                <?= strtoupper(htmlspecialchars($row['judul'])) ?>
              </span>
              <span class="text-xs text-gray-600 text-center">
                <?= htmlspecialchars($row['nama_penulis'] ?? '-') ?>
              </span>
            </div>
          <?php endwhile;
        else: ?>
          <p class="col-span-5 text-center text-gray-500">Tidak ada buku ditemukan.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="w-full text-center py-4 bg-blue-50 text-blue-700 text-sm border-t">
    &copy; <?= date('Y') ?> SIMPENKU - Semua hak cipta dilindungi
  </footer>
</body>

</html>