<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        $incomeCategories = Category::where('user_id', $userId)
            ->where('type', 'income')
            ->orderBy('name')
            ->get();

        $expenseCategories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:income,expense'],
        ]);

        $exists = Category::where('user_id', Auth::user()->id)
            ->where('type', $validated['type'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Kategori dengan nama tersebut sudah ada.',
            ], 422);
        }

        $category = Category::create([
            'user_id' => Auth::user()->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'category' => $category,
        ], 201);
    }

    public function destroy(Category $category)
    {
        abort_if($category->user_id !== Auth::user()->id, 403, 'Anda tidak memiliki akses ke kategori ini.');

        if ($category->transactions()->exists()) {
            return response()->json([
                'message' => 'Kategori masih digunakan oleh transaksi dan tidak dapat dihapus.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
