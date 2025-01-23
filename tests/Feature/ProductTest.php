<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario y token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Crear categoría de prueba
        $this->category = Category::factory()->create();
    }

    public function test_can_get_products()
    {
        Product::factory(3)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/products');

        $response->assertOk()
                ->assertJsonCount(3, 'data');
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $this->category->id,
            'status' => 'active'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/products', $productData);

        $response->assertStatus(202)
                ->assertJson([
                    'success' => true,
                    'message' => 'Product creation has been queued for processing'
                ]);
    }
}
