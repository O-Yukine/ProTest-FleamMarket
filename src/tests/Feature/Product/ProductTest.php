<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    // public function test_anyone_can_access_to_productspage()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_see_all_the_products()
    // {
    //     $products = Product::factory()->count(5)->create();
    //     $categories = Category::factory()->count(3)->create();

    //     foreach ($products as $product) {
    //         $product->categories()->attach($categories->pluck('id')->toArray());
    //     }

    //     $response = $this->get('/');

    //     foreach ($products as $product) {
    //         $response->assertSee($product->name);
    //     }
    // }

    // public function test_only_solditem_have_soldtag()
    // {
    //     $category = Category::factory()->create();

    //     $sold = Product::factory()->create();
    //     $sold->categories()->attach($category->id);

    //     $notSold = Product::factory()->create();
    //     $notSold->categories()->attach($category->id);

    //     Purchase::create([
    //         'id' => 1,
    //         'user_id' => $sold->user->id,
    //         'product_id' => $sold->id,
    //         'payment_method' => 'card',
    //         'post_code' => '123-4567',
    //         'address' => '東京都新宿区',
    //         'building' => null
    //     ]);


    //     $response = $this->get('/');

    //     $response->assertSee('sold');

    // }

    public function test_sellingItem_inNot_showing()
    {

        $user1 = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user1->id,]);

        $user2 = User::factory()->create();

        $this->actingAs($user1);
        $response = $this->get('/?tab=reccommanded');
        $response->assertDontSee($product->name);

        $this->actingAs($user2);
        $response = $this->get('/?tab=reccommanded');
        $response->assertSee($product->name);
    }
}
