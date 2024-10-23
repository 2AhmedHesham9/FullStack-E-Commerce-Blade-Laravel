<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ShopService;
use App\Services\CategoryService;

class ShopController extends Controller
{
    protected $shopservice;

    public function __construct(ShopService $shopservice)
    {
        $this->shopservice = $shopservice;
    }
    public function index(Request $request)
    {
        $response = $this->shopservice->ShowProducts($request);
        $products = $response['products'];
        $size = $response['size']; // number of products per page
        $productSizes = $response['productSizes']; // number of products per page
        $order = $response['order'];
        $brands = $response['brands'];
        $f_brands = $response['f_brands'];
        $categories = $response['categories'];
        $f_categories = $response['f_categories'];
        $min_price = $response['min_price'];
        $max_price = $response['max_price'];
        $max_product_price = $response['max_product_price'];
        $min_product_price = $response['min_product_price'];
        $f_product_size = $response['f_product_size'];
        return view(
            'shop',
            compact(
                'products',
                'productSizes',
                'size',
                'order',
                'brands',
                'f_brands',
                'categories',
                'f_categories',
                'min_price',
                'max_price',
                'max_product_price',
                'min_product_price',
                'f_product_size'

            )
        );
    }

    public function productDetails($slug_product)
    {
        $product = Product::where('slug', $slug_product)->first();
        $sizes = $product->sizes->filter(function ($size) {
            return $size->pivot->quantity > 0;
        })->sortBy('id');
        // dd(array_values($sizes[0]));
        $rproducts = Product::where('slug', '<>', $slug_product)->get()->take(8);
        return view('product-details', compact('product', 'rproducts', 'sizes'));
    }
}
