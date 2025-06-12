<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">
        <!-- Ilustrasi -->
        <div class="bg-blue-200 flex justify-center items-center p-10">
            <img src="uploads/gambar00.png" alt="Ilustrasi" class="max-w-xs md:max-w-sm">
        </div>

        <!-- Form Register -->
        <div class="p-10 flex flex-col justify-center">
            

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Register</h2>
            <p class="text-sm text-gray-500 mb-6">Silakan isi data di bawah untuk membuat akun</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil:</strong> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="includes/auth/proses_registrasi.php" method="POST" class="space-y-4">
                <input type="email" name="email" placeholder="Masukkan email Anda"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>

                <input type="text" name="nama" placeholder="Masukkan Nama Lengkap Anda"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>

                <input type="text" name="nomor_telepon" placeholder="Masukkan Nomor Telepon Anda"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>

                <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="petugas">Petugas</option>
                    <option value="penyewa">Penyewa</option>
                </select>

                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Masukkan Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-10 focus:ring-2 focus:ring-blue-800" required>
                    <span onclick="togglePassword('password', this)"
                        class="absolute right-3 top-3 cursor-pointer text-gray-500">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="relative">
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                        placeholder="Konfirmasi Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-10 focus:ring-2 focus:ring-blue-800" required>
                    <span onclick="togglePassword('konfirmasi_password', this)"
                        class="absolute right-3 top-3 cursor-pointer text-gray-500">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div class="text-right text-sm text-gray-500 mb-4">
                    Sudah punya akun? <a href="login.php" class="text-blue-500 font-semibold hover:underline">Login di sini</a>
                </div>
                <button type="submit"
                    class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition">
                    Daftar
                </button>
            </form>
        </div>
    </div>

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
