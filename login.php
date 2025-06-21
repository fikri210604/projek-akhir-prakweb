<?php session_start();
include 'includes/db.php';

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'penyewa') {
    header("Location: user/dashboard_user.php");
    exit;
}elseif (isset($_SESSION['login']) && $_SESSION['login'] === 'petugas') {
    header("Location: admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-50 shadow-none py-3 px-8 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <img src="https://img.icons8.com/color/48/000000/books.png" class="w-9" alt="Logo">
            <span class="text-xl font-bold text-blue-700">SIMPENKU</span>
        </div>
        <div class="flex gap-3">
            <a href="login.php"
               class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition">Login</a>
            <a href="register.php"
               class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl border border-blue-500 hover:bg-blue-200 transition">Register</a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">
            <!-- Ilustrasi -->
            <div class="bg-blue-200 flex justify-center items-center p-10">
                <img src="uploads/gambar00.png" alt="Ilustrasi" class="max-w-xs md:max-w-sm">
            </div>

            <!-- Form Login -->
            <div class="p-10 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali!</h2>
                <p class="text-sm text-gray-500 mb-6">Masuk</p>

                <!-- Alert Error -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Form Login -->
                <form action="includes/auth/proses_login.php" method="POST" class="space-y-4">
                    <input type="text" name="nama" placeholder="Masukkan Username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>

                    <input type="password" name="password" placeholder="Masukkan Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>

                    <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                        <div>
                            Tidak ada akun?
                            <a href="register.php" class="text-blue-500 font-semibold hover:underline">Register sekarang</a>
                        </div>
                        <div>
                            <a href="#" class="text-blue-500 hover:underline">Recovery Password</a>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script optional untuk toggle password -->
    <script>
        function togglePassword(id, el) {
            const input = document.getElementById(id);
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
