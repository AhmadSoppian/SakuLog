<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (Category::defaults() as $category) {
            Category::create([
                'name' => $category['name'],
                'type' => $category['type'],
            ]);
        }
    }
}
