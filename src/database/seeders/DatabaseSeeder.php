<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(5)->has(Profile::factory())->create();
        $this->call(ConditionTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductTableSeeder::class);
    }
}
