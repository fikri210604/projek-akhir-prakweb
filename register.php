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

<body class="bg-gradient-to-r from-blue-100 to-green-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto my-10 bg-blue-100 rounded-lg shadow-lg p-6">
        <div class="flex flex-col md:flex-row items-center justify-center gap-10">
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="uploads/background.png" alt="Ilustrasi" class="w-80 md:w-full max-w-md object-contain">
            </div>

            <!-- Form Register -->
            <div class="w-full md:w-1/2 max-w-md">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Daftar</h1>
                <p class="text-gray-600 mb-4 text-sm">Silakan isi form untuk membuat akun</p>

                <!-- Alert Error -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Alert Success -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Berhasil:</strong> <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form action="includes/auth/proses_registrasi.php" method="POST" class="flex flex-col gap-4">
                    <input type="email" name="email" placeholder="Masukkan email Anda"
                        class="px-4 py-3 border border-rose-200 rounded-lg" required>

                    <input type="text" name="nama" placeholder="Masukkan Nama Lengkap Anda"
                        class="px-4 py-3 border border-rose-200 rounded-lg" required>

                    <input type="text" name="nomor_telepon" placeholder="Masukkan Nomor Telepon Anda"
                        class="px-4 py-3 border border-rose-200 rounded-lg" required>

                    <select name="role" class="px-4 py-3 border border-rose-200 rounded-lg" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="petugas">Petugas</option>
                        <option value="penyewa">Penyewa</option>
                    </select>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Masukkan Password"
                            class="w-full px-4 py-3 border border-rose-200 rounded-lg pr-10" required>
                        <span onclick="togglePassword('password', this)"
                            class="absolute right-3 top-3 cursor-pointer text-gray-500">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                            placeholder="Konfirmasi Password"
                            class="w-full px-4 py-3 border border-rose-200 rounded-lg pr-10" required>
                        <span onclick="togglePassword('konfirmasi_password', this)"
                            class="absolute right-3 top-3 cursor-pointer text-gray-500">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <button type="submit"
                        class="bg-blue-500 text-white rounded-full py-3 font-semibold hover:bg-blue-600 transition duration-300">
                        Daftar
                    </button>
                </form>

                <p class="text-sm mt-4 text-blue-400">
                    Sudah punya akun?
                    <a href="login.php" class="text-blue-600 hover:underline font-bold">Login di sini</a>
                </p>
            </div>
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