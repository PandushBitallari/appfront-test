<?php

namespace Tests\Feature;

use App\Mail\PriceChangeNotification;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProductPriceChangeNotificationTest extends TestCase
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

    public function test_email_is_sent_when_product_price_changes()
    {
        Mail::fake();

        $product = Product::factory()->create([
            'price' => 100.00,
        ]);

        $product->update(['price' => 150.00]);

        Mail::assertQueued(PriceChangeNotification::class, function ($mail) use ($product) {
            return $mail->hasTo(config('general.price_notification_email'))
                && $mail->product->id === $product->id
                && $mail->oldPrice == 100.00
                && $mail->newPrice == 150.00;
        });
    }
}
