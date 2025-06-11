<?php
include '../../includes/db.php.php';

// Ambil ID dari parameter
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$data = mysqli_fetch_assoc($query);
?>

<div class="container mt-4">
    <h2>Edit User</h2>
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
            <input type="text" name="nomor_telepon" value="<?= $data['nomor_telepon'] ?>" required class="form-control">
        </div>
        <div class="mb-2">
            <label>Role</label>
            <select name="role" required class="form-control">
                <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= $data['role'] == 'user' ? 'selected' : '' ?>>User</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
        <a href="data_user.php" class="btn btn-secondary">Kembali</a>
    </form>
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

    // Jika password diisi, update dengan hash
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
                window.location = 'data_user.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat update data.', 'error');
        </script>";
    }
}
?>
