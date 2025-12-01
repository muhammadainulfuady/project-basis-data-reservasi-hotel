<?php
session_start();
require_once "../config/database.php";

// Cek login tamu
if (!isset($_SESSION['user_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$tamu = $_SESSION['user_login'];

try {
    // QUERY: Menampilkan Ruangan (sesuai laporan)
    $sql = "SELECT * FROM kamar WHERE status = 'belum_dipesan'";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $kamar_tarsedia = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Kamar</title>
</head>

<body>
    <h1>Halo, <?= $tamu['nama_tamu'] ?></h1>
    <a href="../tamu/c-profile.php">Edit Profil</a> | <a href="../auth/login.php">Logout</a>
    <hr>

    <h3>Daftar Kamar Tersedia</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Nomor Kamar</th>
            <th>Tipe</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($kamar_tarsedia as $row): ?>
            <tr>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['tipe_kamar'] ?></td>
                <td>
                    <form action="b-proses_reservasi.php" method="POST">
                        <input type="hidden" name="id_kamar" value="<?= $row['nomor_kamar'] ?>">
                        <button type="submit" name="booking">Pesan Sekarang</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>