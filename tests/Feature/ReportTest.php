<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_reports_page_requires_authentication(): void
    {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_reports_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertOk()
            ->assertSee('Laporan Bulanan');
    }

    public function test_reports_shows_overspend_warning_when_expense_exceeds_income(): void
    {
        $incomeCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Gaji', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Makan', 'type' => 'expense']);

        $this->createTransaction($incomeCategory, 'income', 100000);
        $this->createTransaction($expenseCategory, 'expense', 200000);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertOk()
            ->assertSee('Waspada!');
    }

    public function test_reports_shows_top_expense_category_insight(): void
    {
        $incomeCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Gaji', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Makan', 'type' => 'expense']);

        $this->createTransaction($incomeCategory, 'income', 500000);
        $this->createTransaction($expenseCategory, 'expense', 150000);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertOk()
            ->assertSee('Pengeluaran terbesar')
            ->assertSee('Makan');
    }

    public function test_reports_respects_month_and_year_filter(): void
    {
        $incomeCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Gaji', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Makan', 'type' => 'expense']);

        $this->createTransaction($incomeCategory, 'income', 500000, now()->format('Y-m-d'));

        $lastMonth = now()->subMonth();
        $this->createTransaction($expenseCategory, 'expense', 900000, $lastMonth->format('Y-m-d'));

        $response = $this->actingAs($this->user)->get(route('reports.index', [
            'month' => now()->month,
            'year' => now()->year,
        ]));

        $response->assertOk()
            ->assertSee('Rp 500.000');
    }

    private function createTransaction(Category $category, string $type, float $amount, ?string $date = null): void
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'type' => $type,
            'amount' => $amount,
            'description' => null,
            'transaction_date' => $date ?? now()->format('Y-m-d'),
        ]);
    }
}
