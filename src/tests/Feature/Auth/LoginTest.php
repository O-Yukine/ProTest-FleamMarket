<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_to_loginpage()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }



    public function test_login_validation_email_is_empty()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください',]);
    }

    public function test_Login_validation_password_is_empty()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください',]);
    }

    public function test_Login_validation_information_notmatch()
    {
        $user = User::factory()->create([
            'name' => 'test1',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $this->assertGuest();

        $response->assertSessionHasErrors(['login_error' => 'ログイン情報が登録されていません',]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect('/');
    }
}
