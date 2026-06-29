<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - SakuLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight">SakuLog</h1>
            <p class="text-gray-500 mt-2 text-sm">Buat akun untuk mulai mencatat keuangan</p>
        </div>

        <form id="registerForm" class="space-y-5" novalidate>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="Masukkan nama lengkap">
                <p class="text-xs text-red-600 mt-1 hidden" id="nameError"></p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-900 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="contoh@email.com">
                <p class="text-xs text-red-600 mt-1 hidden" id="emailError"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-1">Password</label>
                <input type="password" id="password" name="password" required minlength="8"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="Minimal 8 karakter">
                <p class="text-xs text-red-600 mt-1 hidden" id="passwordError"></p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="Ulangi password">
                <p class="text-xs text-red-600 mt-1 hidden" id="confirmError"></p>
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition">
                Daftar
            </button>

            <p class="text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-black font-medium underline hover:text-gray-600 transition">Masuk di sini</a>
            </p>
        </form>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirmation');

            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const confirmError = document.getElementById('confirmError');

            [nameError, emailError, passwordError, confirmError].forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            [name, email, password, confirm].forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });

            let valid = true;

            if (!name.value.trim()) {
                nameError.textContent = 'Nama lengkap wajib diisi.';
                nameError.classList.remove('hidden');
                name.classList.remove('border-gray-300');
                name.classList.add('border-red-500');
                valid = false;
            }

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
            } else if (password.value.length < 8) {
                passwordError.textContent = 'Password minimal 8 karakter.';
                passwordError.classList.remove('hidden');
                password.classList.remove('border-gray-300');
                password.classList.add('border-red-500');
                valid = false;
            }

            if (!confirm.value) {
                confirmError.textContent = 'Konfirmasi password wajib diisi.';
                confirmError.classList.remove('hidden');
                confirm.classList.remove('border-gray-300');
                confirm.classList.add('border-red-500');
                valid = false;
            } else if (password.value !== confirm.value) {
                confirmError.textContent = 'Konfirmasi password tidak cocok.';
                confirmError.classList.remove('hidden');
                confirm.classList.remove('border-gray-300');
                confirm.classList.add('border-red-500');
                valid = false;
            }

            if (valid) {
                alert('Pendaftaran berhasil! (backend belum terhubung)');
            }
        });
    </script>
</body>
</html>
