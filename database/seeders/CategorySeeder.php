<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Paket',    'is_active' => true, 'sort' => 1],
            ['name' => 'Makanan',  'is_active' => true, 'sort' => 2],
            ['name' => 'Minuman',  'is_active' => true, 'sort' => 3],
            ['name' => 'Tambahan', 'is_active' => true, 'sort' => 4],
        ];

        foreach ($data as $row) {
            Category::firstOrCreate(
                ['name' => $row['name']],
                ['is_active' => $row['is_active'], 'sort' => $row['sort']]
            );
        }
    }
}
