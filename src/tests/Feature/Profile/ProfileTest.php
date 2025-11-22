<?php

namespace Tests\Feature\profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Purchase;


class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_their_profile()
    {
        $seller = User::factory()->create();
        $purchaseItem = Product::factory()->create([
            'name' => 'ショルダーバッグ',
            'user_id' => $seller->id,
        ]);

        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);
        $user->profile()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿',
            'profile_image' => 'aaa.jpg'
        ]);

        $sellingItem = Product::factory()->create([
            'name' => '腕時計',
            'user_id' => $user->id,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $purchaseItem->id,
            'payment_method' => 'credit',
            'post_code' => $user->profile->post_code,
            'address' => $user->profile->address,
        ]);


        $this->actingAs($user);

        $this->get('/mypage')
            ->assertSee($user->name)
            ->assertSee($user->profile->profile_image)
            ->assertSee($sellingItem->name);

        $this->get('/mypage?page=buy')
            ->assertSee($user->name)
            ->assertSee($user->profile->profile_image)
            ->assertSee($purchaseItem->name);
    }
}
