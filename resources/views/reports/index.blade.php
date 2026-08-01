<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bulanan - SakuLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/report.js'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen">
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold tracking-tight">SakuLog</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-black hidden sm:inline">{{ auth()->user()->name }}</span>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-black transition">Dashboard</a>
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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold">Laporan Bulanan</h2>
                <p class="text-gray-500 text-sm mt-1">Visualisasi dan ringkasan keuangan Anda</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition text-center">
                + Tambah Transaksi
            </a>
        </div>

        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-900 mb-1">Bulan</label>
                <select name="month" id="month"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-900 mb-1">Tahun</label>
                <select name="year" id="year"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    @forelse ($years as $y)
                        <option value="{{ $y }}" {{ $year === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @empty
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforelse
                </select>
            </div>

            <button type="submit"
                class="px-4 py-2.5 rounded-lg text-sm font-medium bg-black text-white hover:bg-gray-800 transition">
                Terapkan
            </button>
        </form>

        @if ($isOverspent)
            <div class="p-4 rounded-lg text-sm mb-6 bg-red-50 text-red-700 border border-red-200">
                <strong>Waspada!</strong> Pengeluaran Anda bulan ini lebih besar daripada pemasukan Anda.
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Pemasukan</p>
                <p class="text-2xl font-bold text-green-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Pengeluaran</p>
                <p class="text-2xl font-bold text-red-700">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-5">
                <p class="text-sm text-gray-500 mb-1">Selisih</p>
                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-black' : 'text-red-700' }}">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </p>
            </div>
        </div>

        @if ($topCategory)
            <div class="border border-gray-200 rounded-lg p-5 mb-6">
                <p class="text-sm text-gray-500">Pengeluaran terbesar Anda bulan ini ada pada kategori
                    <strong class="text-black">{{ $topCategory->name }}</strong> sebesar
                    <strong class="text-black">Rp {{ number_format($topCategory->total, 0, ',', '.') }}</strong>.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg">
                <div class="p-5 border-b border-gray-200">
                    <h3 class="font-semibold">Pengeluaran per Kategori</h3>
                </div>
                <div class="p-5">
                    @if ($expenseCount > 0)
                        <canvas id="pieChart"></canvas>
                    @else
                        <p class="text-sm text-gray-400 py-8 text-center">Belum ada data pengeluaran untuk bulan ini.</p>
                    @endif
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <div class="p-5 border-b border-gray-200">
                    <h3 class="font-semibold">Tren Arus Kas 6 Bulan Terakhir</h3>
                </div>
                <div class="p-5">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.__reportData = @json($reportData);
    </script>
</body>
</html>
