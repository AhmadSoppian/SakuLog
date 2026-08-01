<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        $expensesByCategory = Transaction::where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month)
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->groupBy('transactions.category_id', 'categories.name')
            ->selectRaw('categories.name as name, SUM(transactions.amount) as total')
            ->orderByDesc('total')
            ->get();

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->sum('amount');

        $barLabels = [];
        $barIncome = [];
        $barExpense = [];
        $start = Carbon::create($year, $month, 1)->startOfMonth()->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            $cursor = $start->copy()->addMonths($i);
            $barLabels[] = $cursor->locale('id')->translatedFormat('M y');
            $barIncome[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereYear('transaction_date', $cursor->year)
                ->whereMonth('transaction_date', $cursor->month)
                ->sum('amount');
            $barExpense[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereYear('transaction_date', $cursor->year)
                ->whereMonth('transaction_date', $cursor->month)
                ->sum('amount');
        }

        $years = Transaction::where('user_id', $userId)
            ->selectRaw('EXTRACT(YEAR FROM transaction_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $topCategory = $expensesByCategory->first();

        $reportData = [
            'pieLabels' => $expensesByCategory->pluck('name')->values(),
            'pieValues' => $expensesByCategory->pluck('total')->map(fn ($value) => (float) $value)->values(),
            'pieColors' => $this->pieColors($expensesByCategory->count()),
            'barLabels' => $barLabels,
            'barIncome' => $barIncome,
            'barExpense' => $barExpense,
        ];

        return view('reports.index', [
            'month' => $month,
            'year' => $year,
            'years' => $years,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'isOverspent' => $totalExpense > $totalIncome,
            'topCategory' => $topCategory,
            'expenseCount' => $expensesByCategory->count(),
            'reportData' => $reportData,
        ]);
    }

    private function pieColors(int $count): array
    {
        $palette = [
            '#111827', '#374151', '#6b7280', '#9ca3af', '#d1d5db',
            '#0f172a', '#334155', '#64748b', '#94a3b8', '#cbd5e1',
        ];

        return array_slice($palette, 0, $count);
    }
}
