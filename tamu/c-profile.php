<?php
session_start();
require_once "../config/database.php";

$tamu = $_SESSION['user_login'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_baru = $_POST['nama_tamu'];
    $email_baru = $_POST['email'];
    $telp_baru = $_POST['no_telepon'];
    $id = $tamu['no_identitas'];

    try {
        // QUERY: Update Data Tamu
        $sql = "UPDATE tamu SET nama_tamu = :nama, email = :email, no_telepon = :telp 
                WHERE no_identitas = :id";
        $stmt = $connect->prepare($sql);
        $stmt->execute([
            ':nama' => $nama_baru,
            ':email' => $email_baru,
            ':telp' => $telp_baru,
            ':id' => $id
        ]);

        // Update Session biar data di layar langsung berubah
        $_SESSION['user_login']['nama_tamu'] = $nama_baru;
        $_SESSION['user_login']['email'] = $email_baru;
        $_SESSION['user_login']['no_telepon'] = $telp_baru;

        $message = "Data berhasil diperbarui!";
        $tamu = $_SESSION['user_login']; // Refresh variabel
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<body>
    <h2>Profil Saya</h2>
    <?php if ($message)
        echo "<p style='color:green'>$message</p>"; ?>

    <form method="POST">
        <label>Nama:</label><br>
        <input type="text" name="nama_tamu" value="<?= $tamu['nama_tamu'] ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $tamu['email'] ?>" required><br><br>

        <label>No Telepon:</label><br>
        <input type="text" name="no_telepon" value="<?= $tamu['no_telepon'] ?>" required><br><br>

        <button type="submit">Update Profil</button>
        <a href="kamar.php">Kembali</a>
    </form>
</body>

</html>