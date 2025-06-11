<?php
include '../includes/db.php';

// Hitung jumlah penyewa
$queryPenyewa = mysqli_query($conn, "SELECT COUNT(*) AS total_penyewa FROM users WHERE role = 'user'");
$jumlahPenyewa = mysqli_fetch_assoc($queryPenyewa)['total_penyewa'];

// Hitung jumlah buku
$queryBuku = mysqli_query($conn, "SELECT COUNT(*) AS total_buku FROM buku");
$jumlahBuku = mysqli_fetch_assoc($queryBuku)['total_buku'];

// Hitung jumlah buku yang sedang dipinjam
$querySewa = mysqli_query($conn, "SELECT COUNT(*) AS total_sewa FROM peminjaman WHERE status = 'dipinjam'");
$jumlahSewa = mysqli_fetch_assoc($querySewa)['total_sewa'];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body style="background-color: #E1E1E1;">
    
    <div class="container mt-4">
        <?php include("../asset/sidebar.php"); ?>
        <h2 class="mb-4">Dashboard Admin</h2>
        <div class="alert alert-info" role="alert">
            Selamat datang di dashboard admin! Di sini Anda dapat melihat statistik perpustakaan.
        </div>

        <div class="bg-light p-4 rounded shadow-md mt-3">
            <div class="row g-4">
                <!-- Jumlah Penyewa -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-lines-fill text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="card-title text-muted">Jumlah Penyewa</h6>
                            <p class="display-6 fw-bold text-primary"><?php echo $jumlahPenyewa; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Buku -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-journal-bookmark-fill text-success" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="card-title text-muted">Jumlah Buku</h6>
                            <p class="display-6 fw-bold text-success"><?php echo $jumlahBuku; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Buku Disewa -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-bag-check-fill text-danger" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="card-title text-muted">Buku Sedang Dipinjam</h6>
                            <p class="display-6 fw-bold text-danger"><?php echo $jumlahSewa; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
