<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($transaction) ? 'Ubah Transaksi' : 'Tambah Transaksi' }} - SakuLog</title>
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

    <main class="max-w-xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold">{{ isset($transaction) ? 'Ubah Transaksi' : 'Tambah Transaksi' }}</h2>
            <p class="text-gray-500 text-sm mt-1">Catat pemasukan atau pengeluaran keuangan Anda</p>
        </div>

        <div id="formAlert" class="hidden p-4 rounded-lg text-sm mb-5"></div>

        <form id="transactionForm" method="POST" action="{{ route('transactions.store') }}" class="space-y-5">
            @if (isset($transaction))
                <input type="hidden" name="id" value="{{ $transaction->id }}">
            @endif

            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-900 mb-1">Tanggal Transaksi</label>
                <input type="date" id="transaction_date" name="transaction_date" required
                    value="{{ old('transaction_date', isset($transaction) ? $transaction->transaction_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                <p class="text-xs text-red-600 mt-1 hidden" id="transaction_dateError"></p>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-900 mb-1">Jenis Transaksi</span>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" data-type="income"
                        class="type-option px-4 py-2.5 rounded-lg border text-sm font-medium transition border-gray-300">
                        + Pemasukan
                    </button>
                    <button type="button" data-type="expense"
                        class="type-option px-4 py-2.5 rounded-lg border text-sm font-medium transition border-gray-300">
                        - Pengeluaran
                    </button>
                </div>
                <input type="hidden" name="type" id="type"
                    value="{{ old('type', isset($transaction) ? $transaction->type : 'income') }}">
                <p class="text-xs text-red-600 mt-1 hidden" id="typeError"></p>
            </div>

            <div>
                <label for="category_income" class="block text-sm font-medium text-gray-900 mb-1">Kategori</label>
                <select name="category_id" id="category_income"
                    class="category-select w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($incomeCategories as $category)
                        <option value="{{ $category->id }}" {{ isset($transaction) && $transaction->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select name="category_id" id="category_expense" hidden
                    class="category-select w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($expenseCategories as $category)
                        <option value="{{ $category->id }}" {{ isset($transaction) && $transaction->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-red-600 mt-1 hidden" id="category_idError"></p>
                <a href="{{ route('categories.index') }}" class="inline-block text-xs text-gray-500 hover:text-black underline mt-2 transition">Kelola kategori</a>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-900 mb-1">Nominal (Rp)</label>
                <input type="number" id="amount" name="amount" required min="0.01" step="0.01"
                    value="{{ old('amount', isset($transaction) ? $transaction->amount : '') }}"
                    placeholder="0"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400">
                <p class="text-xs text-red-600 mt-1 hidden" id="amountError"></p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-900 mb-1">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition placeholder:text-gray-400"
                    placeholder="Catatan tambahan (contoh: jajan di kantin)">{{ old('description', isset($transaction) ? $transaction->description : '') }}</textarea>
                <p class="text-xs text-red-600 mt-1 hidden" id="descriptionError"></p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="flex-1 bg-black text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition">
                    {{ isset($transaction) ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
                </button>
                <a href="{{ route('transactions.index') }}"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:text-black hover:border-gray-400 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </main>

    <script>
        const typeInput = document.getElementById('type');
        const typeOptions = document.querySelectorAll('.type-option');
        const incomeSelect = document.getElementById('category_income');
        const expenseSelect = document.getElementById('category_expense');
        const formAlert = document.getElementById('formAlert');

        const DEFAULT_TYPE = typeInput.value || 'income';

        function showAlert(message, type) {
            formAlert.textContent = message;
            formAlert.className = 'p-4 rounded-lg text-sm mb-5';
            if (type === 'success') {
                formAlert.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                formAlert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            formAlert.classList.remove('hidden');
        }

        function hideAlert() {
            formAlert.classList.add('hidden');
            formAlert.textContent = '';
        }

        function setActiveType(type) {
            typeInput.value = type;
            typeOptions.forEach(function (option) {
                const active = option.dataset.type === type;
                option.classList.toggle('bg-black', active);
                option.classList.toggle('text-white', active);
                option.classList.toggle('border-black', active);
                option.classList.toggle('text-gray-600', !active);
            });

            if (type === 'income') {
                expenseSelect.setAttribute('hidden', '');
                incomeSelect.removeAttribute('hidden');
                incomeSelect.disabled = false;
                expenseSelect.disabled = true;
            } else {
                incomeSelect.setAttribute('hidden', '');
                expenseSelect.removeAttribute('hidden');
                expenseSelect.disabled = false;
                incomeSelect.disabled = true;
            }
        }

        typeOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                setActiveType(option.dataset.type);
            });
        });

        function getFieldElement(name) {
            if (name === 'category_id') {
                return typeInput.value === 'income' ? incomeSelect : expenseSelect;
            }
            return document.getElementById(name);
        }

        function setFieldError(name, message) {
            const el = getFieldElement(name);
            const err = document.getElementById(name + 'Error');
            if (!el || !err) return;
            el.classList.remove('border-gray-300');
            el.classList.add('border-red-500');
            err.textContent = message;
            err.classList.remove('hidden');
        }

        function clearFieldError(name) {
            const el = getFieldElement(name);
            const err = document.getElementById(name + 'Error');
            if (!el || !err) return;
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
            err.textContent = '';
            err.classList.add('hidden');
        }

        const fields = ['transaction_date', 'category_id', 'amount', 'description'];
        fields.forEach(function (name) {
            const el = getFieldElement(name);
            if (el) {
                el.addEventListener('input', function () { clearFieldError(name); });
            }
        });

        setActiveType(DEFAULT_TYPE);

        document.getElementById('transactionForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            hideAlert();

            const id = document.querySelector('input[name="id"]')?.value || null;
            const date = document.getElementById('transaction_date');
            const categorySelect = typeInput.value === 'income' ? incomeSelect : expenseSelect;
            const amount = document.getElementById('amount');

            clearFieldError('transaction_date');
            clearFieldError('category_id');
            clearFieldError('amount');
            clearFieldError('type');

            let valid = true;

            if (!date.value) {
                setFieldError('transaction_date', 'Tanggal transaksi wajib diisi.');
                valid = false;
            }

            if (!categorySelect.value) {
                setFieldError('category_id', 'Pilih kategori transaksi.');
                valid = false;
            }

            if (!amount.value) {
                setFieldError('amount', 'Nominal wajib diisi.');
                valid = false;
            } else if (isNaN(amount.value) || Number(amount.value) <= 0) {
                setFieldError('amount', 'Nominal harus berupa angka positif lebih dari 0.');
                valid = false;
            }

            if (!valid) return;

            const url = id
                ? "{{ isset($transaction) ? route('transactions.update', $transaction->id) : '' }}"
                : "{{ route('transactions.store') }}";
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        transaction_date: date.value,
                        type: typeInput.value,
                        category_id: categorySelect.value,
                        amount: amount.value,
                        description: document.getElementById('description').value,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect;
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(function (name) {
                            setFieldError(name, data.errors[name][0]);
                        });
                        if (data.errors.type) {
                            const err = document.getElementById('typeError');
                            err.textContent = data.errors.type[0];
                            err.classList.remove('hidden');
                        }
                    } else {
                        showAlert(data.message || 'Terjadi kesalahan saat menyimpan transaksi.', 'error');
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            }
        });
    </script>
</body>
</html>
