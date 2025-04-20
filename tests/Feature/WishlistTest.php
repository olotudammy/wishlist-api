<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_user_can_add_product_to_wishlist(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson(route('wishlist.store'), [
            'product_id' => $this->product->id,
        ]);

        // dd($response);
        $response->assertOk();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_user_cannot_add_to_wishlist_without_product_id(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson(route('wishlist.store'), []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('product_id');
    }

    public function test_user_can_remove_product_from_wishlist(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->user->wishlist()->create([
            'product_id' => $this->product->id,
        ]);

        $response = $this->deleteJson(route('wishlist.destroy', $this->product->id));

        $response->assertOk();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }
}
