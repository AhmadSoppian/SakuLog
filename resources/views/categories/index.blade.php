<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kategori - SakuLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen">
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold tracking-tight">SakuLog</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-black transition">Dashboard</a>
                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-black transition">Riwayat</a>
                <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-black transition">Kategori</a>
                <a href="{{ route('reports.index') }}" class="text-sm text-black font-medium transition">Laporan</a>
                <span class="text-sm text-gray-500 hidden sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-black transition">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold">Kelola Kategori</h2>
                <p class="text-gray-500 text-sm mt-1">Tambahkan kategori pemasukan dan pengeluaran sesuai kebutuhan Anda</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition text-center">
                + Tambah Transaksi
            </a>
        </div>

        <div id="globalAlert" class="hidden p-4 rounded-lg text-sm mb-5"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ([['type' => 'income', 'title' => 'Kategori Pemasukan', 'categories' => $incomeCategories], ['type' => 'expense', 'title' => 'Kategori Pengeluaran', 'categories' => $expenseCategories]] as $group)
                <div class="border border-gray-200 rounded-lg">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="font-semibold">{{ $group['title'] }}</h3>
                    </div>

                    <form class="category-form p-5 border-b border-gray-200 flex gap-2" data-type="{{ $group['type'] }}" data-delete-url="{{ route('categories.destroy', ':id') }}">
                        <input type="text" name="name" required maxlength="100"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                            placeholder="Nama kategori baru">
                        <button type="submit"
                            class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                            Tambah
                        </button>
                    </form>

                    <div class="p-2">
                        @forelse ($group['categories'] as $category)
                            <div class="flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-gray-50 transition category-row" id="category-{{ $category->id }}">
                                <span class="text-sm">{{ $category->name }}</span>
                                <button type="button" data-action="delete-category"
                                    data-id="{{ $category->id }}"
                                    data-url="{{ route('categories.destroy', $category) }}"
                                    class="text-sm text-red-600 hover:text-red-800 transition">
                                    Hapus
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 p-4">Belum ada kategori. Tambahkan di atas.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <script>
        const globalAlert = document.getElementById('globalAlert');

        function showGlobalAlert(message, type) {
            globalAlert.textContent = message;
            globalAlert.className = 'p-4 rounded-lg text-sm mb-5';
            if (type === 'success') {
                globalAlert.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                globalAlert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            globalAlert.classList.remove('hidden');
        }

        function hideGlobalAlert() {
            globalAlert.classList.add('hidden');
            globalAlert.textContent = '';
        }

        document.querySelectorAll('.category-form').forEach(function (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                hideGlobalAlert();

                const type = form.dataset.type;
                const input = form.querySelector('input[name="name"]');
                const name = input.value.trim();

                if (!name) return;

                try {
                    const response = await fetch("{{ route('categories.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name: name, type: type }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const row = document.createElement('div');
                        row.className = 'flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-gray-50 transition';
                        row.id = 'category-' + data.category.id;
                        row.innerHTML =
                            '<span class="text-sm"></span>' +
                            '<button type="button" data-action="delete-category" data-id="' + data.category.id +
                            '" data-url="' + form.dataset.deleteUrl.replace(':id', data.category.id) +
                            '" class="text-sm text-red-600 hover:text-red-800 transition">Hapus</button>';
                        row.querySelector('span').textContent = data.category.name;

                        const listContainer = form.nextElementSibling;
                        const emptyMessage = listContainer.querySelector('p.text-gray-400');
                        if (emptyMessage) emptyMessage.remove();
                        listContainer.appendChild(row);
                        input.value = '';
                        bindDelete(row.querySelector('[data-action="delete-category"]'));
                        showGlobalAlert(data.message, 'success');
                    } else {
                        showGlobalAlert(data.message || 'Gagal menambahkan kategori.', 'error');
                    }
                } catch (error) {
                    showGlobalAlert('Terjadi kesalahan jaringan', 'error');
                }
            });
        });

        function bindDelete(button) {
            if (!button || button.dataset.bound) return;
            button.dataset.bound = '1';

            button.addEventListener('click', async function () {
                hideGlobalAlert();

                if (!confirm('Yakin ingin menghapus kategori ini?')) return;

                try {
                    const response = await fetch(button.dataset.url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const row = document.getElementById('category-' + button.dataset.id);
                        if (row) row.remove();
                        showGlobalAlert(data.message, 'success');
                    } else {
                        showGlobalAlert(data.message || 'Gagal menghapus kategori.', 'error');
                    }
                } catch (error) {
                    showGlobalAlert('Terjadi kesalahan jaringan', 'error');
                }
            });
        }

        document.querySelectorAll('[data-action="delete-category"]').forEach(bindDelete);
    </script>
</body>
</html>
