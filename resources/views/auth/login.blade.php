<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        @session('success')
            <div class="p-4 rounded-lg text-sm mb-5 bg-green-50 text-green-700 border border-green-200">
                {{ $value }}
            </div>
        @endsession

        <form id="loginForm" class="space-y-5">
            <div id="loginAlert" class="hidden p-4 rounded-lg text-sm mb-5"></div>

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
        const loginAlert = document.getElementById('loginAlert');

        function showAlert(message, type) {
            loginAlert.textContent = message;
            loginAlert.className = 'p-4 rounded-lg text-sm mb-5';
            if (type === 'success') {
                loginAlert.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                loginAlert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            loginAlert.classList.remove('hidden');
        }

        function hideAlert() {
            loginAlert.classList.add('hidden');
            loginAlert.textContent = '';
        }

        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            hideAlert();

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

            if (!valid) return;

            try {
                const response = await fetch("{{ route('login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email.value.trim(),
                        password: password.value,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect;
                } else {
                    if (data.errors) {
                        if (data.errors.email) {
                            emailError.textContent = data.errors.email[0];
                            emailError.classList.remove('hidden');
                            email.classList.remove('border-gray-300');
                            email.classList.add('border-red-500');
                        }
                        if (data.errors.password) {
                            passwordError.textContent = data.errors.password[0];
                            passwordError.classList.remove('hidden');
                            password.classList.remove('border-gray-300');
                            password.classList.add('border-red-500');
                        }
                    } else {
                        showAlert(data.message || 'Email atau password salah', 'error');
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            }
        });
    </script>
</body>
</html>