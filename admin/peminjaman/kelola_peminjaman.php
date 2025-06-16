<?php
session_start();
include '../../includes/db.php';
include '../asset/navbar.php';

// Inisialisasi variabel pencarian dan pagination
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$limit = 10;
$offset = ($page - 1) * $limit;

// Query untuk pencarian
$where = !empty($cari) ? "WHERE u.nama LIKE '%$cari%' OR b.judul LIKE '%$cari%'" : '';

// Query utama
$query = mysqli_query($conn, "
    SELECT p.*, u.nama AS nama_user, b.judul AS judul_buku, b.foto AS foto_buku
    FROM peminjaman p
    JOIN users u ON p.users_id = u.id
    JOIN buku b ON p.buku_id = b.id
    $where
    ORDER BY p.tanggal_pinjam DESC
    LIMIT $limit OFFSET $offset
");

// Hitung total data untuk pagination
$total_query = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM peminjaman p
    JOIN users u ON p.users_id = u.id
    JOIN buku b ON p.buku_id = b.id
    $where
");

$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);
$no = $offset + 1;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Kelola Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card shadow-lg border-0">

            <div class="card-body bg-light">
                <!-- Form Pencarian -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-6 d-flex">
                        <input type="text" name="cari" class="form-control-sm me-2"
                            placeholder="Cari nama atau judul buku..." value="<?= htmlspecialchars($cari ?? '') ?>">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                        <?php if (!empty($cari)): ?>
                            <a href="kelola_peminjaman.php" class="btn btn-secondary me-2">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="text-white" style="background-color: #cfe2ff;">
                            <tr class="fw-bold">
                                <th>No</th>
                                <th>Foto Buku</th>
                                <th>Nama Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <img src="../../uploads/buku/<?= htmlspecialchars($row['foto_buku']) ?>"
                                                alt="Foto Buku" width="70" height="70" class="rounded">
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                        <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                        <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-' ?>
                                        </td>
                                        <td>
                                            <span
                                                class="badge <?= $row['status'] === 'kembali' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-3">
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
</body>

</html>