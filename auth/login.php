<?php
session_start();
require_once "../config/database.php";

$message = "";
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success']; // Pesan dari registrasi
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $no_identitas = $_POST['no_identitas'];
    $password = $_POST['password'];

    try {
        // Cek data di tabel tamu
        $sql = "SELECT * FROM tamu WHERE no_identitas = :id AND password = :pass";
        $stmt = $connect->prepare($sql);
        $stmt->execute([':id' => $no_identitas, ':pass' => $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_login'] = $user;
            // Redirect ke halaman utama (Dashboard/Index)
            header("Location: ../dashboardTamu/index.php");
            exit;
        } else {
            $message = "No Identitas atau Password salah!";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login Hotel</title>
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('../images/image.png');">

    <div class="bg-black/50 backdrop-blur-md p-8 rounded-2xl border border-white/20 shadow-2xl w-[400px]">
        <h2 class="text-white text-3xl font-bold text-center mb-6">🏨 Login</h2>

        <?php if ($message): ?>
            <div class="bg-yellow-500 text-black font-semibold p-3 rounded-lg mb-4 text-center text-sm">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="text-white text-sm font-semibold">No. Identitas</label>
                <input type="number" name="no_identitas" required placeholder="Masukkan No KTP"
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <div>
                <label class="text-white text-sm font-semibold">Password</label>
                <input type="password" name="password" required placeholder="********"
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 text-black font-bold p-3 rounded-xl hover:bg-yellow-500 transition duration-300">
                Masuk
            </button>
        </form>

        <p class="text-center text-white mt-4 text-sm">
            Belum punya akun? <a href="registrasi.php" class="text-yellow-300 hover:underline">Daftar</a>
        </p>
    </div>
</body>

</html>