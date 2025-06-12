<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateProductCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_update_product_command_success()
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old Desc',
            'price' => 10.00,
        ]);

        $exitCode = Artisan::call('product:update', [
            'id' => $product->id,
            '--name' => 'New Name',
            '--description' => 'New Desc',
            '--price' => 20.00,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'description' => 'New Desc',
            'price' => 20.00,
        ]);
        $output = Artisan::output();
        $this->assertStringContainsString('Product updated successfully.', $output);
    }

    public function test_update_product_command_product_not_found()
    {
        $exitCode = Artisan::call('product:update', [
            'id' => 9999,
            '--name' => 'Does Not Matter',
        ]);

        $this->assertEquals(1, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString('Product not found.', $output);
    }
}
