<?php

namespace App\Models;

use App\Mail\PriceChangeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::updated(function ($product) {
            if ($product->isDirty('price')) {
                Mail::to(config('general.price_notification_email'))->send(new PriceChangeNotification($product,
                        $product->getOriginal('price'), $product->price));
            }
        });
    }
}
