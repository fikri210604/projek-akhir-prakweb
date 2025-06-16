<?php
session_start();
include '../../includes/db.php';
include '../asset/sidebar.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

$bukuList = [];
$query = mysqli_query(
    $conn,
    "SELECT buku.*, kategori.nama_kategori 
     FROM buku 
     JOIN kategori ON buku.kategori_id = kategori.id
     WHERE buku.judul LIKE '%$cari%' OR buku.penulis LIKE '%$cari%' OR kategori.nama_kategori LIKE '%$cari%'"
);

while ($row = mysqli_fetch_assoc($query)) {
    $bukuList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body class="bg-light">

    <div class="d-flex">
        <?php include '../asset/sidebar.php'; ?>
    </div>
    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i>Daftar Buku</h5>
                <a href="tambah_buku.php" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Tambah Buku
                </a>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <form method="GET" class="d-flex w-20">
                        <input type="text" name="cari" class="form-control-sm me-2" placeholder="Cari judul buku..."
                            value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                        <?php if ($cari): ?>
                            <a href="kelola_buku.php" class="btn btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
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
                            <?php $no = 1;
                            foreach ($bukuList as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><img src="../../uploads/buku/<?= $row['foto'] ?>" alt="Foto" width="70" height="70">
                                    </td>
                                    <td><?= htmlspecialchars($row['judul']) ?></td>
                                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                                    <td><?= $row['tahun_terbit'] ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] == 'tersedia' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i
                                                class="bi bi-pencil-square"></i></a>
                                        <a href="hapus_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- List Buku Secara Perulangan-->
        <div class="container py-4">
            <div class="row">
                <?php foreach ($bukuList as $buku): ?>
                    <div class="col-md-3 mb-4">
                        <a href="detail_buku.php?id=<?= $buku['id'] ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <img src="../../uploads/buku/<?= $buku['foto'] ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($buku['judul']) ?>" height="180" style="object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($buku['judul']) ?></h5>
                                    <p class="card-text text-muted mb-1"><?= htmlspecialchars($buku['penulis']) ?></p>
                                    <p class="card-text"><small
                                            class="text-primary"><?= htmlspecialchars($buku['nama_kategori']) ?></small></p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <span class="badge bg-<?= $buku['status'] == 'tersedia' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($buku['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>

</html>