<?php
session_start();
require_once "../config/database.php";

// [PENTING] Cek apakah user sudah login sebagai ADMIN
// Hanya admin yang boleh mengakses halaman ini
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'admin'; // Sesuai laporan

    if (!empty($nama_lengkap) && !empty($username) && !empty($password)) {
        try {
            // INSERT Data Admin Baru
            $sql = "INSERT INTO admin (nama_lengkap, username, email, password, role) 
                    VALUES (:nama, :user, :email, :pass, :role)";

            $stmt = $connect->prepare($sql);
            $params = [
                ':nama' => $nama_lengkap,
                ':user' => $username,
                ':email' => $email,
                ':pass' => $password,
                ':role' => $role
            ];

            if ($stmt->execute($params)) {
                // Sukses, kembali ke dashboard
                echo "<script>alert('✅ Berhasil Menambah Admin Baru!'); window.location='dashboard.php';</script>";
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Gagal: Username atau Email sudah terpakai!";
            } else {
                $message = "Terjadi Kesalahan: " . $e->getMessage();
            }
        }
    } else {
        $message = "Mohon lengkapi semua kolom!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Admin - Luxury Hotel</title>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border-t-4 border-red-900">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">🛡️ Tambah Admin Baru</h1>
            <p class="text-gray-500 text-sm">Daftarkan rekan pengelola sistem</p>
        </div>

        <?php if ($message): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center text-sm font-bold border border-red-200">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama Admin"
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Username</label>
                <input type="text" name="username" required placeholder="Username Login"
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Email</label>
                <input type="email" name="email" required placeholder="admin@hotel.com"
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50">
            </div>

            <div>
                <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-red-900 hover:bg-red-800 text-white font-bold p-3 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                    + Simpan Admin
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a href="dashboard.php" class="text-gray-500 hover:text-red-900 text-sm font-semibold transition">← Batal &
                Kembali ke Dashboard</a>
        </div>
    </div>

</body>

</html>