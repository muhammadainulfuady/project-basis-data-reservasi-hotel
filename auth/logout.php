<?php
session_start();
session_destroy(); // Hapus semua sesi login
header("Location: ../index.php"); // Kembali ke halaman utama
exit;
?>