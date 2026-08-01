<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SakuLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen">
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold tracking-tight">SakuLog</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-black hidden sm:inline">{{ auth()->user()->name }}</span>
                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-black transition">Riwayat</a>
                <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-black transition">Kategori</a>
                <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-black transition">Laporan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-black transition">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <p class="text-gray-500 text-sm mt-1">Ringkasan keuangan bulan {{ now()->locale('id')->translatedFormat('F Y') }}</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                + Tambah Transaksi
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Saldo Saat Ini</p>
                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-black' : 'text-red-700' }}">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </p>
            </div>
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Pemasukan Bulan Ini</p>
                <p class="text-2xl font-bold text-green-700">
                    Rp {{ number_format($currentMonthIncome, 0, ',', '.') }}
                </p>
            </div>
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Pengeluaran Bulan Ini</p>
                <p class="text-2xl font-bold text-red-700">
                    Rp {{ number_format($currentMonthExpense, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-lg">
            <div class="p-5 border-b border-gray-200">
                <h3 class="font-semibold">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                @if ($recentTransactions->isEmpty())
                    <p class="text-sm text-gray-400 p-5">Belum ada transaksi. Mulai catat pemasukan atau pengeluaran Anda!</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-500">
                                <th class="text-left py-3 px-5 font-medium">Tanggal</th>
                                <th class="text-left py-3 px-5 font-medium">Kategori</th>
                                <th class="text-left py-3 px-5 font-medium">Keterangan</th>
                                <th class="text-right py-3 px-5 font-medium">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTransactions as $t)
                                <tr class="border-b border-gray-100 last:border-0">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
