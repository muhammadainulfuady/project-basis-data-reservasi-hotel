<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reservasi";

try {
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>