<?php

namespace App\Repositories;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ProductRepository extends BaseRepository
{
    public const MODEL = Product::class;

    public function create(array $input)
    {
        $imageInput = 'product-placeholder.jpg';
        if ( ! empty($input['image'])) {
            $imageInput = $this->uploadImage($input['image']);
        }

        $input = array_merge($input, ['image' => $imageInput]);

        return parent::create($input);
    }

    /**
     * Upload Image
     * @param $file
     * @param  Product|null  $product
     * @return string
     */
    private function uploadImage($file, Product $product = null)
    {
        $filename = time()."_".$file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        if ($product) {
            $oldImage = public_path($product->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }
        }

        return 'uploads/'.$filename;
    }

    public function update(Model $product, array $input)
    {
        if ( ! empty($input['image'])) {
            $imagePath = $this->uploadImage($input['image'], $product);
            $input = array_merge($input, ['image' => $imagePath]);
        }

        return parent::update($product, $input);
    }
}
