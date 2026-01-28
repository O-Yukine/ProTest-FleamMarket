<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private function createProductsForUser($user, $products)
    {
        foreach ($products as $item) {
            $product = $user->products()->create([
                'name' => $item['name'],
                'price' => $item['price'],
                'brand' => $item['brand'],
                'product_image' => $item['product_image'],
                'content' => $item['content'],
                'condition_id' => $item['condition_id'],
            ]);

            $product->categories()->attach($item['categories']);
        }
    }


    public function run()
    {
        $user1 = User::where('email', 'taro@example.com')->first();
        $user2 = User::where('email', 'hanako@example.com')->first();


        $productsForUser1 = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'product_image' => 'clock.jpg',
                'content' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition_id' => 1,
                'categories' => [1, 5],
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'product_image' => 'harddisk.jpg',
                'content' => '高速で信頼性の高いハードディスク',
                'condition_id' => 2,
                'categories' => [3],
            ],

            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'product_image' => 'onion.jpg',
                'content' => '新鮮な玉ねぎ3束のセット',
                'condition_id' => 3,
                'categories' => [10],
            ],

            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'product_image' => 'shoes.jpg',
                'content' => 'クラシックなデザインの革靴',
                'condition_id' => 4,
                'categories' => [1, 5],
            ],

            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'product_image' => 'laptop.jpg',
                'content' => '高性能なノートパソコン',
                'condition_id' => 1,
                'categories' => [2],

            ],
        ];

        $productsForUser2 = [
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'product_image' => 'mic.jpg',
                'content' => '高音質のレコーディング用マイク',
                'condition_id' => 2,
                'categories' => [2],
            ],

            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'product_image' => 'bag.jpg',
                'content' => 'おしゃれなショルダーバッグ',
                'condition_id' => 3,
                'categories' => [1, 4, 12],
            ],

            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'product_image' => 'tumbler.jpg',
                'content' => '使いやすいタンブラー',
                'condition_id' => 4,
                'categories' => [10],
            ],

            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'product_image' => 'coffee_grinder.jpg',
                'content' => '手動のコーヒーミル',
                'condition_id' => 1,
                'categories' => [10],
            ],

            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'product_image' => 'makeup_set.jpg',
                'content' => '便利なメイクアップセット',
                'condition_id' => 2,
                'categories' => [1, 4, 6],
            ],
        ];

        $this->createProductsForUser($user1, $productsForUser1);
        $this->createProductsForUser($user2, $productsForUser2);
    }
}
