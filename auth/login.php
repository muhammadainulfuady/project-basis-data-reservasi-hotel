<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
    style="background-image: url('./images/image.png');">

    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/30 shadow-2xl w-[400px]">
        <h2 class="text-white text-3xl font-bold text-center mb-6">🏨 Luxury Hotel</h2>

        <form action="#" method="POST" class="space-y-5">
            <div>
                <label class="text-white font-semibold">No Identitas</label>
                <input type="text" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <label class="text-white font-semibold">Password</label>
                <input type="password" required
                    class="w-full mt-1 p-3 rounded-xl bg-white/20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 text-black font-bold p-3 rounded-xl hover:bg-yellow-500 transition duration-300">
                Login
            </button>
        </form>

        <p class="text-center text-white mt-4">
            Belum punya akun?
            <a href="./auth/registrasi.php" class="text-yellow-300 hover:underline">Registrasi</a>
        </p>
    </div>
</body>