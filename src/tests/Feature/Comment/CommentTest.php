<?php

namespace Tests\Feature\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_loginUser_can_make_comment()
    {
        $seller = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id
        ]);


        $this->post('/item/' . $product->id . '/comment', [
            'comment' => 'とても素敵です！',
        ]);


        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'とても素敵です！',

        ]);
    }

    public function test_guest_cannot_make_comment()
    {
        $seller = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response =  $this->post('/item/' . $product->id . '/comment', [
            'comment' => 'とても素敵です！',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'comment' => 'とても素敵です！',
        ]);
    }

    public function test_comment_must_be_filled()
    {

        $seller = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $response = $this->post('/item/' . $product->id . '/comment', [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors('comment');

        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
        ]);
    }

    public function test_comment_cannot_exceed_255_characters()
    {

        $seller = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);
        $response = $this->post('/item/' . $product->id . '/comment', [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors('comment');

        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
        ]);
    }
}
