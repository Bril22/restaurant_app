<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => 'Kushi Tsuru',
            'opening_hours' => json_encode(['Mon-Sun' => '11:30 am - 9 pm'])
        ]);
    }
}
