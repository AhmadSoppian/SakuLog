<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $transactions = Transaction::where('user_id', Auth::user()->id)
            ->when($month && $month !== 'all', fn ($query) => $query->whereMonth('transaction_date', $month))
            ->when($year && $year !== 'all', fn ($query) => $query->whereYear('transaction_date', $year))
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $years = Transaction::where('user_id', Auth::user()->id)
            ->selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('transactions.index', compact('transactions', 'month', 'year', 'years'));
    }

    public function create()
    {
        return view('transactions.form', $this->categoryLists());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'category_id' => $data['category_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'transaction_date' => $data['transaction_date'],
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil ditambahkan',
            'redirect' => route('transactions.index'),
            'transaction' => $transaction,
        ], 201);
    }

    public function edit(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        return view('transactions.form', array_merge(
            ['transaction' => $transaction],
            $this->categoryLists()
        ));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        $data = $this->validateData($request);

        $transaction->update([
            'category_id' => $data['category_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'transaction_date' => $data['transaction_date'],
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil diperbarui',
            'redirect' => route('transactions.index'),
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        $transaction->delete();

        return response()->json([
            'message' => 'Transaksi berhasil dihapus',
        ]);
    }

    private function validateData(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'transaction_date' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $category = Category::find($request->input('category_id'));

            if ($category && $category->type !== $request->input('type')) {
                $validator->errors()->add('category_id', 'Kategori tidak cocok dengan jenis transaksi.');
            }
        });

        return $validator->validated();
    }

    private function categoryLists(): array
    {
        $userId = Auth::user()->id;

        return [
            'incomeCategories' => Category::where('user_id', $userId)->where('type', 'income')->orderBy('name')->get(),
            'expenseCategories' => Category::where('user_id', $userId)->where('type', 'expense')->orderBy('name')->get(),
        ];
    }

    private function authorizeAccess(Transaction $transaction): void
    {
        abort_if($transaction->user_id !== Auth::user()->id, 403, 'Anda tidak memiliki akses ke transaksi ini.');
    }
}
