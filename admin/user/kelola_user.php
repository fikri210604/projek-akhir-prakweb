<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'petugas') {
    header("Location: ../login.php");
    exit;
}

include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../asset/navbar.php';

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$limit = ($limit < 1) ? 10 : $limit;
$offset = ($page - 1) * $limit;

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
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <main class="container-fluid py-4">
        <div class="container">
            <h2 class="mb-4 text-center fw-bold text-3xl">Daftar Akun Pengguna</h2>

            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-8 mb-2 mb-md-0">
                            <form method="GET" class="d-flex">
                                <input type="text" name="cari" class="form-control me-2" placeholder="Cari Nama..." value="<?= htmlspecialchars($cari ?? '') ?>">
                                <select name="limit" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                                </select>
                                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i></button>
                                <?php if (!empty($cari)): ?>
                                    <a href="kelola_user.php" class="btn btn-secondary">Reset</a>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="tambah_user.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Pengguna
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle table-striped">
                            <thead class="table-primary">
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
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="text-start"><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= $row['nomor_telepon'] ?? '-' ?></td>
                                            <td>
                                                <span class="badge <?= $row['role'] === 'petugas' ? 'bg-primary' : 'bg-success' ?>">
                                                    <?= ucfirst($row['role']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="crud-user/hapus_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus akun ini?')" title="Hapus">
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
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&cari=<?= urlencode($cari) ?>&limit=<?= $limit ?>">Prev</a>
                            </li>
                            <li class="page-item active">
                                <span class="page-link"><?= $page ?></span>
                            </li>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= min($total_pages, $page + 1) ?>&cari=<?= urlencode($cari) ?>&limit=<?= $limit ?>">Next</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </main>
</body>
</html>