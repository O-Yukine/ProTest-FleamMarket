<?php

namespace Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class ProfileUpdateTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_see_their_profile()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $user->profile()->create([
            'user_id' => $user->id,
            'profile_image' => 'aaa.jpg',
            'post_code' => '123-4567',
            'address' => '東京都渋谷',
            'building' => 'ビルディング３号',
        ]);

        $this->actingAs($user)->get('/mypage/profile')
            ->assertSee($user->profile->profile_image)
            ->assertSee($user->profile->post_code)
            ->assertSee($user->profile->address)
            ->assertSee($user->profile->building);
    }
}
