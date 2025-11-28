<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    public function test_anyone_can_access_to_productspage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guest_can_see_all_the_products()
    {
        $names = ['商品1', '商品2', '商品3', '商品4', '商品5'];

        $products = collect();
        foreach ($names as $name) {
            $products->push(
                Product::factory()->create(['name' => $name])
            );
        }

        $categories = Category::factory()->count(3)->create();

        foreach ($products as $product) {
            $product->categories()->attach($categories->pluck('id')->toArray());
        }

        $response = $this->get('/');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_soldItem_have_soldTag()
    {
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        Purchase::create([
            'status' => 'paid',
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'credit',
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => null
        ]);

        $response = $this->get('/');
        $response->assertSee('sold');
    }

    public function test_mySellingItem_inNot_onRecommendedPage()
    {

        $user1 = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'ショルダーバッグ',
            'user_id' => $user1->id,
        ]);

        $user2 = User::factory()->create();

        $this->actingAs($user1);
        $response = $this->get('/?tab=recommended');
        $response->assertDontSee($product->name);

        $this->actingAs($user2);
        $response = $this->get('/?tab=recommended');
        $response->assertSee($product->name);
    }
}
