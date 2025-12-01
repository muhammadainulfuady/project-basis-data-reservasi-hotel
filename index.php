<?php
require_once("./config/database.php");
$ambil_reservasi = $connect->prepare("SELECT * FROM reservasi");
$ambil_reservasi->execute();
$data = $ambil_reservasi->fetchAll();
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./src/output.css" rel="stylesheet">
    <title>Login</title>
</head>

<?php
require_once "./auth/login.php"
    ?>

</html>