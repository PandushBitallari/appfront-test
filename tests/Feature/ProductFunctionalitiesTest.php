<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFunctionalitiesTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_products_index_displays_products()
    {
        $this->authenticate();
        Product::factory()->count(3)->create();

        $response = $this->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_can_show_product()
    {
        $this->authenticate();
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewHas('product', $product);
    }

    public function test_can_create_product()
    {
        $this->authenticate();
        $data = [
            'name' => 'Test Product Test',
            'description' => 'Test Description',
            'price' => 99.99,
        ];

        $response = $this->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $product = Product::where($data)->latest()->first();

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_can_update_product()
    {
        $this->authenticate();
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'description' => $product->description,
            'price' => $product->price,
        ];

        $response = $this->put(route('admin.products.update', $product->id), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_product()
    {
        $this->authenticate();
        $product = Product::factory()->create();

        $response = $this->delete(route('admin.products.destroy', $product->id));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
