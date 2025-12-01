<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <title>Registrasi</title>
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('../images/image.png');">

    <div class="bg-white/10 backdrop-blur-md p-10 rounded-2xl border border-white/30 shadow-2xl w-[700px]">
        <h2 class="text-white text-3xl font-bold text-center mb-6">🏨 Luxury Hotel</h2>

        <form action="#" method="POST" class="grid grid-cols-2 gap-5">
            <div>
                <label class="text-white font-semibold">No Identitas</label>
                <input type="text" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <label class="text-white font-semibold">Nama</label>
                <input type="text" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <label class="text-white font-semibold">Password</label>
                <input type="password" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <label class="text-white font-semibold">Email</label>
                <input type="text" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <label class="text-white font-semibold">No Telpon</label>
                <input type="text" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 text-black font-bold p-3 rounded-xl hover:bg-yellow-500 transition duration-300">
                Registrasi
            </button>
        </form>

        <p class="text-center text-white mt-4">
            Sudah punya akun?
            <a href="../index.php" class="text-yellow-300 hover:underline">Login</a>
        </p>
    </div>
</body>

</html>