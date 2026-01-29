<?php


namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Stripe\Checkout\Session;
use Mockery;


class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_purchase()

    {
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);
        $buyer = User::factory()->create();

        $this->actingAs($buyer);

        $mock = Mockery::mock('overload:' . Session::class);
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/test_checkout'
            ]);

        $response = $this->post(
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

        $response->assertRedirect('https://checkout.stripe.com/test_checkout');
    }

    public function test_soldItem_have_soldTag()
    {
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        $this->actingAs($buyer);

        $mock = Mockery::mock('overload:' . Session::class);
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/test_checkout'
            ]);

        $this->post(
            '/purchase/' . $product->id,
            [
                'payment_method' => 'credit',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => null,

            ]
        );

        $purchase = Purchase::first();

        $this->assertEquals('paid', $purchase->status);

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

        $mock = Mockery::mock('overload:' . Session::class);
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/test_checkout'
            ]);

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
