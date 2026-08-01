<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function defaults(): array
    {
        return [
            ['name' => 'Gaji Part Time', 'type' => 'income'],
            ['name' => 'Uang Saku', 'type' => 'income'],
            ['name' => 'Beasiswa', 'type' => 'income'],
            ['name' => 'Freelance', 'type' => 'income'],
            ['name' => 'Lainnya', 'type' => 'income'],
            ['name' => 'Makan & Minum', 'type' => 'expense'],
            ['name' => 'Transportasi', 'type' => 'expense'],
            ['name' => 'Internet/Pulsa', 'type' => 'expense'],
            ['name' => 'Hiburan', 'type' => 'expense'],
            ['name' => 'Uang Kos', 'type' => 'expense'],
            ['name' => 'Print/Tugas Kuliah', 'type' => 'expense'],
            ['name' => 'Lainnya', 'type' => 'expense'],
        ];
    }

    public static function createDefaultsFor(User $user): void
    {
        foreach (self::defaults() as $category) {
            self::create([
                'user_id' => $user->id,
                'name' => $category['name'],
                'type' => $category['type'],
            ]);
        }
    }
}
