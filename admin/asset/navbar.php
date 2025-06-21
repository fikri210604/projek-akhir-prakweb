<nav id="navbar" class="fixed top-0 w-full z-50 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-screen-xl mx-auto px-4 lg:px-6 py-3 flex justify-between items-center">
        <!-- Logo dan Nama -->
        <?php
        // Tentukan link tujuan berdasarkan status login
        $homeUrl = (isset($_SESSION['login']) && $_SESSION['login'] === 'true') 
            ? base_url('admin/dashboard.php') 
            : base_url('index.php'); 
        ?>
        <a href="<?= $homeUrl ?>" class="flex items-center space-x-2">
        <img src="<?= base_url('uploads/logo.png') ?>" class="h-8 w-auto" alt="Logo">
        <span class="text-xl font-semibold text-gray-800 dark:text-white">SIMPENKU</span>
        </a>

        <!-- Menu -->
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === 'true'): ?>
                <a href="<?= base_url('admin/dashboard.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Dashboard</a>
                <a href="<?= base_url('admin/user/kelola_user.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Kelola User</a>
                <a href="<?= base_url('admin/penulis/kelola_penulis.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Kelola Penulis</a>
                <a href="<?= base_url('admin/kategori/kelola_kategori_buku.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Kategori</a>
                <a href="<?= base_url('admin/buku/kelola_buku.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Buku</a>
                <a href="<?= base_url('admin/peminjaman/kelola_peminjaman.php') ?>" class="text-gray-700 hover:text-blue-500 transition">Peminjaman</a>

                <!-- Dropdown User -->
                <div class="relative inline-block text-left">
                    <button id="dropdownButton" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                        </svg>
                        <span><?= htmlspecialchars($_SESSION['nama'] ?? 'User', ENT_QUOTES) ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-md shadow-lg z-50 dark:bg-gray-800 dark:border-gray-600">
                        <a href="edit_profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Edit Profil</a>
                        <a href="<?= base_url('logout.php') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
                    </div>
                </div>

                <!-- JS Dropdown Toggle -->
                <script>
                    const dropdownBtn = document.getElementById('dropdownButton');
                    const dropdownMenu = document.getElementById('dropdownMenu');

                    dropdownBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdownMenu.classList.toggle('hidden');
                    });

                    document.addEventListener('click', (e) => {
                        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                            dropdownMenu.classList.add('hidden');
                        }
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Beri jarak konten dengan navbar -->
<div class="mt-20"></div>
