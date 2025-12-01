<?php
require_once "database.php";

// tamu registrasi
function registrasi(array $data)
{
    global $connect;
    $register = $connect->prepare("INSERT INTO tamu 
    (no_identitas, nama_tamu, password, email, no_telepon) 
    VALUES (:no_identitas, :nama_lengkap, :password, :email, :no_telpon)");
    $result = $register->execute([
        ":no_identitas" => $data["no_identitas"],
        ":nama_lengkap" => $data["nama_lengkap"],
        ":password" => $data["password"],
        ":email" => $data["email"],
        ":no_telpon" => $data["no_telpon"]
    ]);
    if ($result && $register->rowCount() > 0) {
        $_SESSION['BERHASIL_REGISTER'] = "Anda berhasil register";
        header("Location: ../index.php");
        exit;
    }

}


?>