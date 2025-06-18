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
    $query = mysqli_query($conn, "SELECT * FROM penulis WHERE nama_penulis LIKE '%$cari%' LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM penulis WHERE nama_penulis LIKE '%$cari%'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM penulis LIMIT $limit OFFSET $offset");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM penulis");
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
    <title>Data Penulis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <main class="container-fluid py-4">
        <div class="container">
            <h2 class="mb-4 text-center fw-bold">Daftar Penulis</h2>

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
                                    <a href="kelola_penulis.php" class="btn btn-secondary">Reset</a>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="tambah_penulis.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Penulis
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama Penulis</th>
                                    <th>Biografi</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Kebangsaan</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($query) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php
                                                $foto_path = '../../uploads/penulis/' . htmlspecialchars($row['foto']);
                                                if (empty($row['foto']) || !file_exists($foto_path)) {
                                                    $foto_path = '../../uploads/penulis/default.png';
                                                }
                                                ?>
                                                <img src="<?= $foto_path ?>" alt="Foto Penulis" width="70" height="70" class="rounded">
                                            </td>
                                            <td class="text-start"><?= htmlspecialchars($row['nama_penulis']) ?></td>
                                            <td><?= htmlspecialchars($row['bio']) ?></td>
                                            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                                            <td><?= htmlspecialchars($row['kebangsaan']) ?></td>
                                            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                                            <td>
                                                <a href="edit_penulis.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="hapus_penulis.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus penulis ini?')" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
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
