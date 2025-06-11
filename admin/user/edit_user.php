<?php
include '../../includes/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <div class="container mt-4">
        <h2>Edit User</h2>
        <div class="container p-4 bg-light rounded shadow-lg">
            <form method="post">
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $data['email'] ?>" required class="form-control">
                </div>
                <div class="mb-2">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?= $data['nama'] ?>" required class="form-control">
                </div>
                <div class="mb-2">
                    <label>Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" value="<?= $data['nomor_telepon'] ?>" required
                        class="form-control">
                </div>
                <div class="mb-2">
                    <label>Role</label>
                    <select name="role" required class="form-control">
                        <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="penyewa" <?= $data['role'] == 'penyewa' ? 'selected' : '' ?>>Penyewa</option>
                    </select>

                </div>
                <div class="mb-2">
                    <label>Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
                <a href="kelola_user.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

    <!-- Tambahkan SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    if (isset($_POST['update'])) {
        $email = $_POST['email'];
        $nama = $_POST['nama'];
        $nohp = $_POST['nomor_telepon'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET email='$email', nama='$nama', nomor_telepon='$nohp', role='$role', password='$password' WHERE id=$id";
        } else {
            $query = "UPDATE users SET email='$email', nama='$nama', nomor_telepon='$nohp', role='$role' WHERE id=$id";
        }

        $update = mysqli_query($conn, $query);

        if ($update) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data user berhasil diperbarui.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'kelola_user.php';
            });
        </script>";
        } else {
            echo "<script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat update data.', 'error');
        </script>";
        }
    }
    ?>

</body>

</html>