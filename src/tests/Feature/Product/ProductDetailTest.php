<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    // public function test_product_details_are_showing()
    // {

    //     $user = User::factory()->create();
    //     $profile = Profile::create([
    //         'user_id' => $user->id,
    //         'profile_image' => 'aaa.jpg',
    //         'post_code' => '123-456',
    //         'address' => '東京都渋谷区',
    //         'building' => null

    //     ]);

    //     $condition = Condition::create([
    //         'name' => '良好'
    //     ]);

    //     $category = Category::factory()->create();
    //     $product = Product::factory()->create([
    //         'condition_id' => $condition->id,
    //     ]);
    //     $product->categories()->attach($category->id);

    //     $comment = Comment::create([
    //         'product_id' => $product->id,
    //         'user_id' => $user->id,
    //         'comment' => 'どれくらい使用してますか？'
    //     ]);

    //     $user->likes()->attach($product->id);


    //     $response = $this->get('/item/' . $product->id);

    //     $response->assertSee($product->name);
    //     $response->assertSee($product->brand);
    //     $response->assertSee((string)$product->price);
    //     $response->assertSee($product->content);
    //     $response->assertSee($product->product_image);


    //     $product->categories->each(function ($cat) use ($response) {
    //         $response->assertSee($cat->name);
    //     });

    //     $response->assertSee($product->condition->name);
    //     $response->assertSee($comment->comment);
    //     $response->assertSee($comment->user->name);
    //     $response->assertSee($profile->profile_image);

    //     $response->assertSee((string)$product->comments->count());
    //     $response->assertSee((string)$product->likedBy->count());
    // }

    public function test_all_the_categories_are_showing()
    {
        $categories = Category::factory()->count(2)->create();
        $categories[0]->update(['name' => 'メンズ']);
        $categories[1]->update(['name' => 'レディース']);

        $product = Product::factory()->create();

        foreach ($categories as $category) {
            $product->categories()->attach($category->id);
        };

        $response = $this->get('/item/' . $product->id);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        };
    }
}
