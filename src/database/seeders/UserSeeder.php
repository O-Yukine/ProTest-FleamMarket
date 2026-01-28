<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taro = User::create([
            'name' => '香川 太郎',
            'email' => 'taro@example.com',
            'password' => bcrypt('password'),

        ]);

        $hanako = User::create([
            'name' => '高松 花子',
            'email' => 'hanako@example.com',
            'password' => bcrypt('password'),

        ]);

        $jiro = User::create([
            'name' => '丸亀 二郎',
            'email' => 'jiro@example.com',
            'password' => bcrypt('password'),

        ]);

        Profile::factory()->create(['user_id' => $taro->id]);
        Profile::factory()->create(['user_id' => $hanako->id]);
        Profile::factory()->create(['user_id' => $jiro->id]);
    }
}
