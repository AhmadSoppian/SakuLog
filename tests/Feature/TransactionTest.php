<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $incomeCategory;

    private Category $expenseCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->incomeCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Gaji', 'type' => 'income']);
        $this->expenseCategory = Category::create(['user_id' => $this->user->id, 'name' => 'Makan', 'type' => 'expense']);
    }

    public function test_transactions_page_requires_authentication(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_transactions_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('transactions.index'));

        $response->assertOk();
    }

    public function test_user_can_access_create_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('transactions.create'));

        $response->assertOk();
    }

    public function test_user_can_create_transaction(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('transactions.store'), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'income',
            'category_id' => $this->incomeCategory->id,
            'amount' => 50000,
            'description' => 'Uang saku mingguan',
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Transaksi berhasil ditambahkan',
                'redirect' => route('transactions.index'),
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'type' => 'income',
            'amount' => 50000,
        ]);
    }

    public function test_amount_must_be_positive(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('transactions.store'), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'income',
            'category_id' => $this->incomeCategory->id,
            'amount' => 0,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_amount_must_not_be_negative(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('transactions.store'), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'expense',
            'category_id' => $this->expenseCategory->id,
            'amount' => -1000,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_amount_is_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('transactions.store'), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'income',
            'category_id' => $this->incomeCategory->id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_category_must_match_type(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('transactions.store'), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'income',
            'category_id' => $this->expenseCategory->id,
            'amount' => 10000,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id']);

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_user_can_update_transaction(): void
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'category_id' => $this->incomeCategory->id,
            'type' => 'income',
            'amount' => 50000,
            'description' => 'Gaji part time',
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->user)->putJson(route('transactions.update', $transaction), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'expense',
            'category_id' => $this->expenseCategory->id,
            'amount' => 20000,
            'description' => 'Jajan',
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Transaksi berhasil diperbarui',
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'type' => 'expense',
            'amount' => 20000,
        ]);
    }

    public function test_user_can_delete_transaction(): void
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'category_id' => $this->incomeCategory->id,
            'type' => 'income',
            'amount' => 50000,
            'description' => 'Gaji part time',
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('transactions.destroy', $transaction));

        $response->assertOk()
            ->assertJson([
                'message' => 'Transaksi berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_user_cannot_manage_others_transaction(): void
    {
        $otherUser = User::factory()->create();
        $transaction = Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $this->incomeCategory->id,
            'type' => 'income',
            'amount' => 50000,
            'description' => 'Milik user lain',
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $update = $this->actingAs($this->user)->putJson(route('transactions.update', $transaction), [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'income',
            'category_id' => $this->incomeCategory->id,
            'amount' => 10000,
        ]);
        $update->assertForbidden();

        $delete = $this->actingAs($this->user)->deleteJson(route('transactions.destroy', $transaction));
        $delete->assertForbidden();

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }
}
