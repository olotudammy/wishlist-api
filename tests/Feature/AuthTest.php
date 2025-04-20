<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_it_creates_a_user_and_returns_success_json_response(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ];

        $response = $this->postJson(route('register'), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Login was successful',
            'data' => [
                'user' => [
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                ],
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_it_logs_in_and_returns_auth_token_and_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson(route('login'), $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'token',
                'user' => ['id', 'name', 'email'],
                'token_refresh_code',
                'token_expires',
            ],
        ]);

        $refreshCode = $response->getData()->data->token_refresh_code;
        $this->assertTrue(Cache::has($refreshCode));
    }

    public function test_authenticated_user_can_logout(): void
    {

        $this->actingAs($this->user, 'sanctum');

        $token = $this->user->createToken('auth_token');
        $this->assertCount(1, $this->user->tokens);

        $response = $this->postJson(route('logout'));

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'User logged out successfully',
            ]);

        $this->assertCount(0, $this->user->fresh()->tokens);
    }
}
