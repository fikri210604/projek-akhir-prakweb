<?php
include __DIR__ . '/../../includes/db.php';

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$limit = 10;
$offset = ($page - 1) * $limit;

if (!empty($cari)) {
    $query = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori LIKE '%$cari%' LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori WHERE nama_kategori LIKE '%$cari%'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM kategori LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori");
}

$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);
$no = $offset + 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <div class="card shadow border-0">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-folder-fill me-2"></i>Daftar Kategori</h5>
                <a href="tambah_kategori.php" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
                </a>
            </div>

            <div class="card-body">
                <!-- Form Pencarian -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4 d-flex">
                        <input type="text" name="cari" class="form-control form-control-sm me-2"
                            placeholder="Cari nama kategori..." value="<?= htmlspecialchars($cari ?? '') ?>">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <?php if (!empty($cari)): ?>
                            <a href="kelola_kategori.php" class="btn btn-secondary btn-sm ms-2">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr class="text-center align-middle">
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <img src="../../uploads/kategori/<?= htmlspecialchars($row['foto']) ?>" alt="Foto"
                                                width="70" height="70" class="rounded">
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                        <td>
                                            <a href="edit_kategori.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="crud-user/hapus_kategori.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-danger" onclick="return confirm('Hapus Kategori ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Halaman" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?page=<?= max(1, $page - 1) ?>&cari=<?= urlencode($cari) ?>">Prev</a>
                        </li>
                        <li class="page-item active"><span class="page-link"><?= $page ?></span></li>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?page=<?= min($total_pages, $page + 1) ?>&cari=<?= urlencode($cari) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Font Awesome (untuk ikon edit/hapus jika digunakan) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>