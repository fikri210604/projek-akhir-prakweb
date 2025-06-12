<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-100 to-green-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto my-10 bg-blue-200 rounded-lg shadow-sm p-6 outline outline-1 outline-green-500">
        <div class="flex flex-col md:flex-row items-center justify-center gap-10">
            <!-- Gambar -->
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="uploads/background.png" alt="Ilustrasi" class="w-80 md:w-full max-w-md object-contain">
            </div>

            <!-- Form Login -->
            <div class="w-full md:w-1/2 max-w-md">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Masuk</h1>
                <p class="text-gray-600 mb-4 text-sm">
                    Silahkan Masukkan Nama Lengkap dan Password Anda
                </p>

                <!-- Alert Error -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="includes/auth/proses_login.php" method="POST" class="flex flex-col gap-4">
                    <input type="email" name="email" placeholder="Masukkan email Anda"
                        class="px-4 py-3 border border-rose-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-300"
                        required>

                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Masukkan Password"
                            class="w-full px-4 py-3 border border-rose-200 rounded-lg pr-10" required>
                        <span onclick="togglePassword('password', this)"
                            class="absolute right-3 top-3 cursor-pointer text-gray-500">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <button type="submit"
                        class="bg-blue-500 text-white rounded-full py-3 font-semibold hover:bg-blue-600 transition duration-300">
                        Masuk
                    </button>
                </form>

                <p class="text-sm mt-4 text-blue-400">
                    Belum punya akun?
                    <a href="register.php" class="text-blue-700 hover:underline font-bold">Daftar di sini</a>
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