<?php
session_start();

if (isset($_SESSION['alert'])) {
    $type = $_SESSION['alert']['type'];
    $message = $_SESSION['alert']['message'];

    $color = $type === 'success' ? 'green' : 'red';

    echo "<div class='bg-$color-100 border border-$color-400 text-$color-700 px-4 py-3 rounded mb-4'>
            <strong class='font-bold'>" . ucfirst($type) . "!</strong>
            <span class='block sm:inline'>$message</span>
          </div>";

    unset($_SESSION['alert']);
}
?>
