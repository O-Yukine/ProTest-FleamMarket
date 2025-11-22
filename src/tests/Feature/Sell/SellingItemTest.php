<?php

namespace Tests\Feature\Sell;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Http\UploadedFile;


class SellingItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_sell_items()
    {

        $seller = User::factory()->create();

        $condition = Condition::create([
            'name' => '良好',
        ]);

        $categories = Category::factory()->count(2)->create();
        $categories[0]->update(['name' => 'ファッション']);
        $categories[1]->update(['name' => 'レディース']);

        $file = UploadedFile::fake()->create('aaa.jpg', 100);

        $this->actingAs($seller);


        $this->post('/sell', [
            'name' => 'ショルダーバッグ',
            'product_image' => $file,
            'brand' => 'プラダ',
            'price' => 15000,
            'content' => '新品未開封',
            'condition_id' => $condition->id,
            'categories' => [$categories[0]->id, $categories[1]->id],

        ]);

        $product = Product::latest()->first();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'ショルダーバッグ',
            'brand' => 'プラダ',
            'price' => 15000,
            'product_image' => 'aaa.jpg',
            'content' => '新品未開封',
            'condition_id' => $condition->id,
        ]);

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_product', [
                'product_id' => $product->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
