<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'petugas') {
    header("Location: ../login.php");
    exit;
}
include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../asset/navbar.php';

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$limit = in_array($limit, [5, 10, 25, 50]) ? $limit : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
<main class="container-fluid py-4">
    <div class="container">
        <h2 class="mb-4 text-center fw-bold">Kelola Kategori Buku</h2>

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-8 mb-2 mb-md-0">
                        <form method="GET" class="d-flex">
                            <input type="text" name="cari" class="form-control me-2" placeholder="Cari Nama Kategori..." value="<?= htmlspecialchars($cari); ?>">
                            <select name="limit" class="form-select me-2" onchange="this.form.submit()" style="width: auto;">
                                <option value="5" <?= ($limit == 5) ? 'selected' : ''; ?>>5</option>
                                <option value="10" <?= ($limit == 10) ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?= ($limit == 25) ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?= ($limit == 50) ? 'selected' : ''; ?>>50</option>
                            </select>
                            <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i></button>
                            <?php if (!empty($cari)): ?>
                                <a href="kelola_kategori_buku.php" class="btn btn-secondary">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="tambah_kategori_buku.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query && mysqli_num_rows($query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?php
                                            $fotoPath = '../../uploads/kategori/' . $row['foto_kategori'];
                                            if (!empty($row['foto_kategori']) && file_exists($fotoPath)): ?>
                                                <img src="<?= $fotoPath ?>" alt="Foto" width="60" height="60" class="rounded">
                                            <?php else: ?>
                                                <span class="text-muted">Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="edit_kategori_buku.php?id=<?= urlencode($row['id_kategori'] ?? '') ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="hapus_kategori_buku.php?id=<?= urlencode($row['id_kategori'] ?? '') ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
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

                <?php if ($total_pages > 1): ?>
                    <nav class="mt-4" aria-label="Navigasi halaman">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1); ?>&cari=<?= urlencode($cari); ?>&limit=<?= $limit; ?>">Prev</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($cari); ?>&limit=<?= $limit; ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= min($total_pages, $page + 1); ?>&cari=<?= urlencode($cari); ?>&limit=<?= $limit; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>

</html>
