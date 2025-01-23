<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create([
            'name' => 'SALA 1',
            'ubication' => 'BLOQUE A',
            'description' => 'Zona de Computo'
        ]);

        Category::create([
            'name' => 'SALA 2',
            'ubication' => 'BLOQUE A',
            'description' => 'Zona de Computo'
        ]);
    }
}
