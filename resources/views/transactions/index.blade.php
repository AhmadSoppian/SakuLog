<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Transaksi - SakuLog</title>
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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold">Riwayat Transaksi</h2>
                <p class="text-gray-500 text-sm mt-1">Semua pemasukan dan pengeluaran Anda</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition text-center">
                + Tambah Transaksi
            </a>
        </div>

        <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-900 mb-1">Bulan</label>
                <select name="month" id="month"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    <option value="all" {{ $month === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (string) $month === (string) $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-900 mb-1">Tahun</label>
                <select name="year" id="year"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    <option value="all" {{ $year === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach ($years as $y)
                        <option value="{{ $y }}" {{ (string) $year === (string) $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="px-4 py-2.5 rounded-lg text-sm font-medium bg-black text-white hover:bg-gray-800 transition">
                Terapkan
            </button>
        </form>

        <div class="border border-gray-200 rounded-lg">
            <div class="overflow-x-auto">
                @if ($transactions->isEmpty())
                    <p class="text-sm text-gray-400 p-8 text-center">Tidak ada transaksi pada periode ini.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-500">
                                <th class="text-left py-3 px-5 font-medium">Tanggal</th>
                                <th class="text-left py-3 px-5 font-medium">Kategori</th>
                                <th class="text-left py-3 px-5 font-medium">Keterangan</th>
                                <th class="text-right py-3 px-5 font-medium">Nominal</th>
                                <th class="text-right py-3 px-5 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $t)
                                <tr class="border-b border-gray-100 last:border-0" id="transaction-{{ $t->id }}">
                                    <td class="py-3 px-5 whitespace-nowrap">{{ $t->transaction_date->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 px-5">
                                        <span class="inline-block px-2 py-0.5 text-xs rounded {{ $t->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $t->category->name ?? ($t->type === 'income' ? 'Pemasukan' : 'Pengeluaran') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 text-gray-600 max-w-[200px] truncate">{{ $t->description ?: '-' }}</td>
                                    <td class="py-3 px-5 text-right font-medium whitespace-nowrap {{ $t->type === 'income' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-5 text-right whitespace-nowrap">
                                        <a href="{{ route('transactions.edit', $t) }}"
                                            class="text-sm text-gray-500 hover:text-black transition mr-3">Ubah</a>
                                        <button type="button" data-action="delete" data-id="{{ $t->id }}"
                                            data-url="{{ route('transactions.destroy', $t) }}"
                                            class="text-sm text-red-600 hover:text-red-800 transition">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @if ($transactions->hasPages())
                <div class="p-5 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-action="delete"]').forEach(function (button) {
            button.addEventListener('click', async function () {
                const id = button.dataset.id;
                const url = button.dataset.url;

                if (!confirm('Yakin ingin menghapus transaksi ini?')) return;

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (response.ok) {
                        document.getElementById('transaction-' + id).remove();

                        const rows = document.querySelectorAll('tbody tr');
                        if (rows.length === 0) {
                            window.location.reload();
                        }
                    } else {
                        alert(data.message || 'Gagal menghapus transaksi.');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan jaringan');
                }
            });
        });
    </script>
</body>
</html>
