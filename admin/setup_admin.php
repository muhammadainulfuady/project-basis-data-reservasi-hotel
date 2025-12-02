<?php
// ==========================================
// 1. KONEKSI DATABASE
// ==========================================
require_once "../config/database.php";

// ==========================================
// 2. DATA ADMIN BARU (Sesuai Laporan)
// ==========================================
// Data ini diambil dari tabel "Fitur: Isi Data Admin" di Laporan Progress
$nama_lengkap = 'Admins';
$username = 'admin_test';
$email = 'andi@example.com';
$password = 'adminpass1'; // Password plain text (sesuai laporan)
$role = 'admin';

try {
    // Cek dulu apakah username sudah ada biar tidak error duplikat
    $cek = $connect->prepare("SELECT COUNT(*) FROM admin WHERE username = :username");
    $cek->execute([':username' => $username]);

    if ($cek->fetchColumn() > 0) {
        echo "<h3>⚠️ Gagal: Username '$username' sudah ada di database.</h3>";
    } else {
        // ==========================================
        // 3. QUERY: ISI DATA ADMIN (INSERT)
        // ==========================================
        // Sesuai Laporan: 
        // INSERT INTO admin (nama_lengkap, username, email, password, role) VALUES (...)

        $sql = "INSERT INTO admin (nama_lengkap, username, email, password, role) 
                VALUES (:nama, :user, :email, :pass, :role)";

        $stmt = $connect->prepare($sql);
        $stmt->execute([
            ':nama' => $nama_lengkap,
            ':user' => $username,
            ':email' => $email,
            ':pass' => $password,
            ':role' => $role
        ]);

        echo "<h3>✅ Berhasil: Akun Admin '$username' telah dibuat!</h3>";
        echo "<p>Silakan login di <a href='../auth/login.php'>Halaman Login</a></p>";
    }

} catch (PDOException $e) {
    echo "Error Database: " . $e->getMessage();
}
?>