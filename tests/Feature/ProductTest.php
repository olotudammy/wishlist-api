<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_get_products(): void
    {

        $this->actingAs($this->user, 'sanctum');

        $products = Product::factory()->count(3)->create();

        $response = $this->getJson(route('products.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'description'],
                ],
            ]);

        foreach ($products as $product) {
            $response->assertJsonFragment(['name' => $product->name]);
        }
    }
}
