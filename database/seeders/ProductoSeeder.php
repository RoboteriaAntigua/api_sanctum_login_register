<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('productos')->insert([
            'name' => 'iphone 123',
            'description' => 'un telefono',
            'price' => 900
        ]);
        DB::table('productos')->insert([
            'name' => 'otro 123',
            'description' => 'un telefono',
            'price' => 322
        ]);
        DB::table('productos')->insert([
            'name' => 'teclado 123',
            'description' => 'un telefono',
            'price' => 123
        ]);
    }
}
