<?php
session_start();
require_once "../config/database.php";

$message = "";

// Cek apakah user sudah login, jika ya lempar ke index
if (isset($_SESSION['user_login'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // 1. Ambil data dari form
    $no_identitas = $_POST['no_identitas'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telpon = $_POST['no_telpon'];
    $password = $_POST['password'];

    // 2. Validasi sederhana
    if (!empty($no_identitas) && !empty($nama_lengkap) && !empty($password)) {
        try {
            // 3. Query SQL INSERT (Sesuai dengan Laporan & Struktur Tabel)
            // Kolom: no_identitas, nama_tamu, password, email, no_telepon
            $sql = "INSERT INTO tamu (no_identitas, nama_tamu, password, email, no_telepon) 
                    VALUES (:id, :nama, :pass, :email, :telp)";

            $stmt = $connect->prepare($sql);

            // 4. Binding Data
            $params = [
                ":id" => $no_identitas,
                ":nama" => $nama_lengkap,
                ":pass" => $password, // Password disimpan langsung (sesuai request), bisa di-hash jika perlu
                ":email" => $email,
                ":telp" => $no_telpon
            ];

            // 5. Eksekusi
            if ($stmt->execute($params)) {
                $_SESSION['success'] = "Registrasi Berhasil! Silakan Login.";
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            // Cek error duplikat (misal No Identitas sudah ada)
            if ($e->getCode() == 23000) {
                $message = "Gagal: No. Identitas tersebut sudah terdaftar!";
            } else {
                $message = "Terjadi Kesalahan: " . $e->getMessage();
            }
        }
    } else {
        $message = "Mohon lengkapi semua kolom wajib!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registrasi - Luxury Hotel</title>
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('../images/image.png');">

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 bg-black/60 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-2xl w-[500px]">
        <div class="text-center mb-6">
            <h2 class="text-white text-3xl font-bold tracking-wide">🏨 Buat Akun Baru</h2>
            <p class="text-gray-300 text-sm mt-2">Bergabunglah untuk pengalaman menginap terbaik</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="bg-red-500/90 text-white p-3 rounded-lg mb-4 text-center text-sm font-semibold">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">

            <div>
                <label class="text-white text-sm font-semibold ml-1">No. Identitas (KTP/SIM)</label>
                <input type="number" name="no_identitas" required placeholder="Contoh: 350..."
                    class="w-full mt-1 p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
            </div>

            <div>
                <label class="text-white text-sm font-semibold ml-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama Anda"
                    class="w-full mt-1 p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-white text-sm font-semibold ml-1">Email</label>
                    <input type="email" name="email" required placeholder="email@anda.com"
                        class="w-full mt-1 p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
                </div>
                <div>
                    <label class="text-white text-sm font-semibold ml-1">No. HP</label>
                    <input type="text" name="no_telpon" required placeholder="0812..."
                        class="w-full mt-1 p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
                </div>
            </div>

            <div>
                <label class="text-white text-sm font-semibold ml-1">Password</label>
                <input type="password" name="password" required placeholder="Buat Password Aman"
                    class="w-full mt-1 p-3 rounded-xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 border border-white/10 transition">
            </div>

            <button type="submit"
                class="w-full bg-yellow-500 text-black font-bold p-3 rounded-xl mt-6 hover:bg-yellow-400 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-white mt-6 text-sm">
            Sudah punya akun?
            <a href="login.php" class="text-yellow-400 hover:text-yellow-300 font-bold hover:underline">Login di
                sini</a>
        </p>
    </div>
</body>

</html>