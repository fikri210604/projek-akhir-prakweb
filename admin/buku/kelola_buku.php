<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'petugas') {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db.php';
include '../asset/navbar.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Query total data
$sqlCount = "SELECT COUNT(*) as total FROM buku 
    JOIN kategori ON buku.kategori_id = kategori.id 
    JOIN penulis ON buku.penulis_id = penulis.id 
    WHERE buku.judul LIKE '%$cari%' 
    OR penulis.nama_penulis LIKE '%$cari%' 
    OR kategori.nama_kategori LIKE '%$cari%'";

$countResult = mysqli_query($conn, $sqlCount);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// Query data buku
$query = mysqli_query(
    $conn,
    "SELECT buku.*, kategori.nama_kategori, penulis.nama_penulis 
     FROM buku
     JOIN kategori ON buku.kategori_id = kategori.id
     JOIN penulis ON buku.penulis_id = penulis.id
     WHERE buku.judul LIKE '%$cari%' 
        OR penulis.nama_penulis LIKE '%$cari%' 
        OR kategori.nama_kategori LIKE '%$cari%'
     ORDER BY buku.id DESC
     LIMIT $limit OFFSET $offset"
);

$bukuList = [];
while ($row = mysqli_fetch_assoc($query)) {
    $bukuList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
<main class="container-fluid py-4">
    <div class="container">
        <h2 class="mb-4 text-center fw-bold text-3xl">Kelola Buku</h2>

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-8 mb-2 mb-md-0">
                        <form method="GET" class="d-flex">
                            <input type="text" name="cari" class="form-control me-2" placeholder="Cari Judul / Penulis / Kategori..." value="<?= htmlspecialchars($cari) ?>">
                            <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i></button>
                            <?php if (!empty($cari)): ?>
                                <a href="kelola_buku.php" class="btn btn-secondary">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="tambah_buku.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Buku
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = $offset + 1; foreach ($bukuList as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php $foto = '../../uploads/buku/' . $row['foto']; ?>
                                        <?php if (!empty($row['foto']) && file_exists($foto)): ?>
                                            <img src="<?= $foto ?>" alt="Foto Buku" width="60" height="60" class="rounded">
                                        <?php else: ?>
                                            <span class="text-muted">Tidak Ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['judul']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_penulis']) ?></td>
                                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                                    <td><?= $row['tahun_terbit'] ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td>
                                        <?php
                                        $status = ($row['jumlah'] == 0) ? 'tidak tersedia' : $row['status'];
                                        $badgeClass = ($status == 'tersedia') ? 'success' : (($status == 'dipinjam') ? 'warning' : 'danger');
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                            <a href="hapus_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus buku ini?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($bukuList) === 0): ?>
                                <tr><td colspan="10" class="text-center text-muted">Tidak ada data ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&cari=<?= urlencode($cari) ?>">Prev</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&cari=<?= urlencode($cari) ?>">Next</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</main>
</body>
</html>
