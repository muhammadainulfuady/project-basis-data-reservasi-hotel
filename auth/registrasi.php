<?php
session_start();
require_once "../config/database.php";

$message = "";

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
            // 3. Query SQL (Perhatikan: Placeholder :nama TIDAK BOLEH pakai kutip)
            $sql = "INSERT INTO tamu (no_identitas, nama_tamu, password, email, no_telepon) 
                    VALUES (:id, :nama, :pass, :email, :telp)";

            $stmt = $connect->prepare($sql);

            // 4. Binding Data
            $params = [
                ":id" => $no_identitas,
                ":nama" => $nama_lengkap,
                ":pass" => $password, // Disarankan pakai password_hash($password, PASSWORD_DEFAULT) jika ingin aman
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
            // Cek duplikat data (Error Code 23000)
            if ($e->getCode() == 23000) {
                $message = "No Identitas sudah terdaftar!";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
    } else {
        $message = "Mohon lengkapi semua data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registrasi Tamu</title>
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('../images/image.png');">

    <div class="bg-black/50 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-2xl w-[500px]">
        <h2 class="text-white text-3xl font-bold text-center mb-6">🏨 Daftar Akun</h2>

        <?php if ($message): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center text-sm">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="text-white text-sm font-semibold">No. Identitas (KTP)</label>
                <input type="number" name="no_identitas" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <div>
                <label class="text-white text-sm font-semibold">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-white text-sm font-semibold">Email</label>
                    <input type="email" name="email" required
                        class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="text-white text-sm font-semibold">No. HP</label>
                    <input type="text" name="no_telpon" required
                        class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
            </div>

            <div>
                <label class="text-white text-sm font-semibold">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 text-black font-bold p-3 rounded-xl hover:bg-yellow-500 transition duration-300 mt-4">
                Registrasi
            </button>
        </form>

        <p class="text-center text-white mt-4 text-sm">
            Sudah punya akun? <a href="login.php" class="text-yellow-300 hover:underline">Login</a>
        </p>
    </div>
</body>

</html>