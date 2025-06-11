<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$baseUrl = '/projek-akhir-prakweb/';
?>

<div class="d-flex">
  <!-- Sidebar -->
  <nav id="sidebarMenu" class="text-white p-3"
    style="width: 250px; min-height: 100vh; transition: width 0.3s; background-color: #002E5F;" data-state="open">
    <div class="d-flex align-items-center mb-3" style="gap: 10px;">
      <img src="<?= $baseUrl ?>public/img/asset/logo.png" alt="Logo Admin" class="img-fluid"
        style="width: 50px; height: auto;">
      <h4 class="text-white m-0" id="sidebarTitle" style="font-family: 'Montserrat', cursive; font-size: 20px;">Admin Panel</h4>
    </div>
    <ul class="nav flex-column mt-3">

      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link text-white mt-3 <?= ($currentPage == 'dashboard') ? 'active bg-primary rounded' : '' ?>"
          href="<?= $baseUrl ?>admin/dashboard.php">
          <i class="bi bi-house"></i> <span class="link-text">Dashboard</span>
        </a>
      </li>

      <!-- User -->
      <li class="nav-item">
        <a class="nav-link text-white mt-3 <?= ($currentPage == 'kelola_user') ? 'active bg-primary rounded' : '' ?>"
          href="<?= $baseUrl ?>admin/user/kelola_user.php">
          <i class="bi bi-people"></i> <span class="link-text">Data User</span>
        </a>
      </li>

      <!-- Buku -->
      <li class="nav-item">
        <a class="nav-link text-white mt-3 <?= ($currentPage == 'kelola_buku') ? 'active bg-primary rounded' : '' ?>"
          href="<?= $baseUrl ?>admin/buku/kelola_buku.php">
          <i class="bi bi-book"></i> <span class="link-text">Data Buku</span>
        </a>
      </li>

      <!-- Kategori Buku -->
      <li class="nav-item">
        <a class="nav-link text-white mt-3 <?= ($currentPage == 'kelola_kategori_buku') ? 'active bg-primary rounded' : '' ?>"
          href="<?= $baseUrl ?>admin/kategori/kelola_kategori_buku.php">
          <i class="bi bi-tags"></i> <span class="link-text">Kategori Buku</span>
        </a>
      </li>
      <!-- Peminjaman -->
      <li class="nav-item">
        <a class="nav-link text-white mt-3 <?= ($currentPage == 'kelola_peminjaman') ? 'active bg-primary rounded' : '' ?>"
          href="<?= $baseUrl ?>admin/peminjaman/kelola_peminjaman.php">
          <i class="bi bi-journal-bookmark-fill"></i> <span class="link-text">Peminjaman</span>
        </a>
      </li>
      <!-- Logout -->
      <li class="nav-item">
        <a class="nav-link text-white bg-danger rounded mt-3" href="<?= $baseUrl ?>../../logout.php">
          <i class="bi bi-box-arrow-right"></i> <span class="link-text">Logout</span>
        </a>
      </li>

    </ul>

    <!-- Toggle Button -->
    <button id="toggleSidebar" class="btn btn-outline-light d-flex align-items-center gap-2 mt-4" type="button">
      <span class="toggle-icon"><i class="bi bi-arrow-left-circle-fill"></i></span>
    </button>
  </nav>

  <!-- Toggle Sidebar Script -->
  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebarMenu');
    const texts = document.querySelectorAll('.link-text');
    const title = document.getElementById('sidebarTitle');
    const toggleIcon = document.querySelector('.toggle-icon i');

    toggleBtn.addEventListener('click', function () {
      const isOpen = sidebar.getAttribute('data-state') === 'open';

      if (isOpen) {
        sidebar.style.width = '80px';
        texts.forEach(t => t.style.display = 'none');
        title.style.display = 'none';
        toggleIcon.className = 'bi bi-arrow-right-circle-fill';
        sidebar.setAttribute('data-state', 'collapsed');
      } else {
        sidebar.style.width = '250px';
        texts.forEach(t => t.style.display = 'inline');
        title.style.display = 'block';
        toggleIcon.className = 'bi bi-arrow-left-circle-fill';
        sidebar.setAttribute('data-state', 'open');
      }
    });
  </script>
</div>
