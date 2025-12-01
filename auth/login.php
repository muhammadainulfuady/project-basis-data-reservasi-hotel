<?php
session_start();
require_once "../config/database.php";

// Jika sudah login sebagai tamu, lempar ke index
if (isset($_SESSION['user_login'])) {
    header("Location: ../index.php");
    exit;
}
// Jika sudah login sebagai admin, lempar ke dashboard admin
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$message = "";
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Kita namakan variabel ini 'identifier' karena bisa berupa No Identitas (Tamu) atau Username (Admin)
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    try {
        // ------------------------------------------
        // 1. CEK KE TABEL TAMU (Prioritas Tamu)
        // ------------------------------------------
        // Query disesuaikan dengan laporan: WHERE no_identitas = ... AND password = ...
        $sql_tamu = "SELECT * FROM tamu WHERE no_identitas = :id AND password = :pass";
        $stmt_tamu = $connect->prepare($sql_tamu);
        $stmt_tamu->execute([':id' => $identifier, ':pass' => $password]);
        $tamu = $stmt_tamu->fetch(PDO::FETCH_ASSOC);

        if ($tamu) {
            // Login Berhasil sebagai TAMU
            $_SESSION['user_login'] = $tamu;
            header("Location: ../index.php");
            exit;
        }

        // ------------------------------------------
        // 2. CEK KE TABEL ADMIN (Jika bukan Tamu)
        // ------------------------------------------
        // Query disesuaikan dengan laporan: WHERE username = ... AND password = ...
        $sql_admin = "SELECT * FROM admin WHERE username = :user AND password = :pass";
        $stmt_admin = $connect->prepare($sql_admin);
        $stmt_admin->execute([':user' => $identifier, ':pass' => $password]);
        $admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Login Berhasil sebagai ADMIN / PENGELOLA
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            $_SESSION['admin_role'] = $admin['role']; // 'admin' atau 'pengelola'

            header("Location: ../admin/dashboard.php");
            exit;
        }

        // Jika sampai sini berarti tidak ada di kedua tabel
        $message = "Username/No. Identitas atau Password salah!";

    } catch (PDOException $e) {
        $message = "Error Database: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login - Luxury Hotel</title>
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('../images/image.png');">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 bg-black/60 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-2xl w-[400px]">
        <div class="text-center mb-8">
            <h2 class="text-white text-3xl font-bold tracking-wide">🏨 Luxury Hotel</h2>
            <p class="text-gray-300 text-sm mt-2">Masuk ke akun Anda</p>
        </div>

        <?php if (!empty($message)): ?>
            <div
                class="bg-yellow-500/90 text-black font-semibold p-3 rounded-lg mb-6 text-center text-sm shadow-md animate-pulse">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-white text-sm font-semibold mb-2">Username / No. Identitas</label>
                <input type="text" name="identifier" required placeholder="Ketik Username atau No KTP"
                    class="w-full p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
            </div>

            <div>
                <label class="block text-white text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
            </div>

            <button type="submit"
                class="w-full bg-yellow-500 text-black font-bold p-3 rounded-xl hover:bg-yellow-400 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                MASUK SEKARANG
            </button>
        </form>

        <div class="mt-6 text-center border-t border-white/10 pt-4">
            <p class="text-white text-sm">
                Belum punya akun?
                <a href="registrasi.php"
                    class="text-yellow-400 hover:text-yellow-300 font-bold hover:underline transition">Daftar Tamu</a>
            </p>
            <p class="mt-2 text-xs text-gray-400">
                <a href="../index.php" class="hover:text-white">← Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</body>

</html>