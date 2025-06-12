<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $exchangeRate;
    private $productRepository;

    /**
     * @param  ProductRepository  $productRepository
     * @param  ExchangeRateService  $exchangeRateService
     */
    public function __construct(ProductRepository $productRepository, ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRate = $exchangeRateService->getExchangeRate();
        $this->productRepository = $productRepository;
    }

    /**
     * Fetches all products and retrieves the current exchange rate to display in the product list view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->productRepository->getAll();
        $exchangeRate = $this->exchangeRate;

        return view('products.list', compact('products', 'exchangeRate'));
    }

    /**
     * Display the specified product view.
     *
     * @param  Product  $product
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Product $product, Request $request)
    {
        $exchangeRate = $this->exchangeRate;

        return view('products.show', compact('product', 'exchangeRate'));
    }
}
