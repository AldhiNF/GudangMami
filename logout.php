<?php
session_start();
session_destroy(); // Hapus semua tiket dan sesi
header("Location: login.php"); // Balikin ke halaman login
exit;
?>