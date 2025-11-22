<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Stripe\Checkout\Session;



class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_the_product()

    {
        $seller = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        $this->actingAs($buyer);

        $this->post(
            '/purchase/' . $product->id,
            [
                'payment_method' => 'credit',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => null,

            ]
        );

        $this->assertDatabaseHas('purchases', [
            'product_id' => $product->id,
            'user_id' => $buyer->id,
        ]);
    }

    public function test_soldItem_have_soldTag()
    {
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        $this->actingAs($buyer);

        $this->post(
            '/purchase/' . $product->id,
            [
                'payment_method' => 'credit',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => null,

            ]
        );

        $response = $this->get('/?tab=recommended');
        $response->assertSeeInOrder([$product->name, 'sold']);
    }
    public function test_purchased_item_appears_in_mypage()
    {

        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        $this->actingAs($buyer);

        $this->post(
            '/purchase/' . $product->id,
            [
                'payment_method' => 'credit',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => null,

            ]
        );

        $response = $this->get('/mypage?page=buy');
        $response->assertSee($product->name);
    }
}
