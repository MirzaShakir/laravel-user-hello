<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Hello;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Factory::create();
        foreach( range(1, 25 ) as $index ) {
            DB::query('truncate table hellos');
            Hello::create([
                'name' => $faker->firstName,
                'order' => $faker->numberBetween(1, 10)
            ]);
        }
    }
}
