<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login admin</title>
</head>

<body>
    <form action="#" method="POST">
        <label for="no_identitas">No Identitas :</label>
        <input type="text" name="no_identitas">
        <br>
        <label for="nama_tamu">Nama Tamu :</label>
        <input type="text" name="nama_tamu">
        <br>
        <label for="password">Password :</label>
        <input type="text" name="password">
        <br>
        <label for="email">Email :</label>
        <input type="text" name="email">
        <br>
        <label for="no_telpon">NO Telpon :</label>
        <input type="text" name="no_telpon">
        <br>
        <select name="admin_or_siswa" id="admin_or_siswa">
            <option value="admin">Admin</option>
            <option value="admin">Siswa</option>
        </select>
        <br>
        <button type="submit" name="submit">Login</button>
    </form>
</body>

</html>