<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;

class ShippingAddressTest extends TestCase
{

    use RefreshDatabase;

    public function test_shipping_address_can_be_changed()
    {

        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();
        Profile::create([
            'user_id' => $buyer->id,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $this->actingAs($buyer);

        $response = $this->get('/purchase/' . $product->id);
        $response->assertSee($buyer->profile->post_code);
        $response->assertSee($buyer->profile->address);

        $response = $this->post('/purchase/address/' . $product->id, [
            'post_code' => '987-6543',
            'address' => '沖縄県那覇市'
        ]);

        $response->assertRedirect('/purchase/' . $product->id);

        $response = $this->get('/purchase/' . $product->id);
        $response->assertSee('987-6543');
        $response->assertSee('沖縄県那覇市');

        $response->assertDontSee('123-4567');
        $response->assertDontSee('東京都渋谷区');
    }


    public function test_shipping_address_is_saved_with_purchase()
    {

        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();
        Profile::create([
            'user_id' => $buyer->id,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $this->actingAs($buyer);


        $this->post('/purchase/' . $product->id, [
            'payment_method' => 'credit',
            'post_code' => '987-6543',
            'address' => '沖縄県那覇市',
        ]);

        $this->assertDatabaseHas('purchases', [
            'product_id' => $product->id,
            'user_id' => $buyer->id,
            'post_code' => '987-6543',
            'address' => '沖縄県那覇市',


        ]);
    }
}
