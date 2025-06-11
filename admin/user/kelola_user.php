<?php
include __DIR__ . '/../../includes/db.php';
// include __DIR__ . '/../asset/sidebar.php';

// Inisialisasi variabel pencarian dan pagination
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$limit = 10;
$offset = ($page - 1) * $limit;

// Query total data
if (!empty($cari)) {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE nama LIKE '%$cari%' LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE nama LIKE '%$cari%'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM users LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
}

$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);
$no = $offset + 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body style="background-color: #f5f5f5;">
    <div class="container py-5">
        <h2 class="mb-4 text-center fw-bold">Daftar Akun Pengguna</h2>

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <!-- Tombol Tambah -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" class="d-flex w-50">
                        <input type="text" name="cari" class="form-control me-2" placeholder="Cari Nama..."
                            value="<?= htmlspecialchars($cari ?? '') ?>">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        <?php if (!empty($cari)): ?>
                            <a href="kelola_user.php" class="btn btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>

                    <a href="tambah_user.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Pengguna
                    </a>
                </div>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-primary text-white text-center table-bordered table-striped">
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>Nomor Telepon</th>
                                <th>Role</th>
                                <th>Dibuat Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td class="text-start"><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= $row['nomor_telepon'] ?? '-' ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?= $row['role'] === 'petugas' ? 'bg-primary' : 'bg-success' ?>">
                                                <?= ucfirst($row['role']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                        <td>
                                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="crud-user/hapus_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus akun ini?')" title="Hapus">
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

                <!-- Pagination -->
                <nav aria-label="Navigasi halaman" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&cari=<?= urlencode($cari) ?>">Prev</a>
                        </li>
                        <li class="page-item active">
                            <span class="page-link"><?= $page ?></span>
                        </li>
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