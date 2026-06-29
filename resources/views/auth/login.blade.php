<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SakuLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight">SakuLog</h1>
            <p class="text-gray-500 mt-2 text-sm">Masuk ke akun Anda</p>
        </div>

        <form id="loginForm" class="space-y-5" novalidate>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-900 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="contoh@email.com">
                <p class="text-xs text-red-600 mt-1 hidden" id="emailError"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="Masukkan password">
                <p class="text-xs text-red-600 mt-1 hidden" id="passwordError"></p>
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition">
                Masuk
            </button>

            <p class="text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-black font-medium underline hover:text-gray-600 transition">Daftar di sini</a>
            </p>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            emailError.classList.add('hidden');
            emailError.textContent = '';
            passwordError.classList.add('hidden');
            passwordError.textContent = '';
            email.classList.remove('border-red-500');
            email.classList.add('border-gray-300');
            password.classList.remove('border-red-500');
            password.classList.add('border-gray-300');

            let valid = true;

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim()) {
                emailError.textContent = 'Email wajib diisi.';
                emailError.classList.remove('hidden');
                email.classList.remove('border-gray-300');
                email.classList.add('border-red-500');
                valid = false;
            } else if (!emailPattern.test(email.value.trim())) {
                emailError.textContent = 'Format email tidak valid.';
                emailError.classList.remove('hidden');
                email.classList.remove('border-gray-300');
                email.classList.add('border-red-500');
                valid = false;
            }

            if (!password.value) {
                passwordError.textContent = 'Password wajib diisi.';
                passwordError.classList.remove('hidden');
                password.classList.remove('border-gray-300');
                password.classList.add('border-red-500');
                valid = false;
            }

            if (valid) {
                alert('Login berhasil! (backend belum terhubung)');
            }
        });
    </script>
</body>
</html>
