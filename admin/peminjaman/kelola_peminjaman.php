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


<body style="background-color: #f8f9fa;">
    <main class="container-fluid py-4">
        <div class="container">
            <h2 class="mb-4 text-center fw-bold">Daftar Peminjaman Buku</h2>

            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-12">
                            <form method="GET" class="d-flex">
                                <input type="text" name="cari" class="form-control me-2"
                                    placeholder="Cari nama peminjam atau judul buku..."
                                    value="<?= htmlspecialchars($cari) ?>">
                                <select name="limit" class="form-select me-2" onchange="this.form.submit()"
                                    style="width: auto;">
                                    <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
                                    <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                                    <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                                </select>
                                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i></button>
                                <?php if (!empty($cari)): ?>
                                    <a href="kelola_peminjaman.php" class="btn btn-secondary">Reset</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Foto Buku</th>
                                    <th>Nama Peminjam</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <img src="../../uploads/buku/<?= htmlspecialchars($row['foto_buku']) ?>"
                                                    alt="Foto Buku" width="70" height="70" class="rounded mx-auto">
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
                                            <td>
                                                <a href="hapus_peminjaman.php?id=<?= $row['id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus peminjaman ini?')" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav class="mt-4" aria-label="Navigasi halaman">
                        <?php if ($total_pages > 1): ?>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="?page=<?= max(1, $page - 1) ?>&cari=<?= urlencode($cari) ?>&limit=<?= $limit ?>">Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link"><?= $page ?></span>
                                </li>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="?page=<?= min($total_pages, $page + 1) ?>&cari=<?= urlencode($cari) ?>&limit=<?= $limit ?>">Next</a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </nav>

                </div>
            </div>
        </div>
    </main>
</body>

</html>