<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_categories_page_requires_authentication(): void
    {
        $response = $this->get(route('categories.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_categories_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.index'));

        $response->assertOk();
    }

    public function test_user_can_create_category(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('categories.store'), [
            'name' => 'Gaji Bulanan',
            'type' => 'income',
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Kategori berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('categories', [
            'user_id' => $this->user->id,
            'name' => 'Gaji Bulanan',
            'type' => 'income',
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('categories.store'), [
            'name' => '',
            'type' => 'expense',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_category_name_must_be_unique_per_user_and_type(): void
    {
        Category::create(['user_id' => $this->user->id, 'name' => 'Hiburan', 'type' => 'expense']);

        $response = $this->actingAs($this->user)->postJson(route('categories.store'), [
            'name' => 'Hiburan',
            'type' => 'expense',
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseCount('categories', 1);
    }

    public function test_same_category_name_allowed_across_different_types(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('categories.store'), [
            'name' => 'Lainnya',
            'type' => 'income',
        ]);
        $response->assertCreated();

        $response = $this->actingAs($this->user)->postJson(route('categories.store'), [
            'name' => 'Lainnya',
            'type' => 'expense',
        ]);
        $response->assertCreated();

        $this->assertDatabaseCount('categories', 2);
    }

    public function test_user_can_delete_own_category(): void
    {
        $category = Category::create(['user_id' => $this->user->id, 'name' => 'Hiburan', 'type' => 'expense']);

        $response = $this->actingAs($this->user)->deleteJson(route('categories.destroy', $category));

        $response->assertOk()
            ->assertJson([
                'message' => 'Kategori berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_others_category(): void
    {
        $otherUser = User::factory()->create();
        $category = Category::create(['user_id' => $otherUser->id, 'name' => 'Hiburan', 'type' => 'expense']);

        $response = $this->actingAs($this->user)->deleteJson(route('categories.destroy', $category));

        $response->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_category_in_use(): void
    {
        $category = Category::create(['user_id' => $this->user->id, 'name' => 'Makan', 'type' => 'expense']);
        Transaction::create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 10000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('categories.destroy', $category));

        $response->assertUnprocessable();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
