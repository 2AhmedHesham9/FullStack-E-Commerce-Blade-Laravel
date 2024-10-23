<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->paginate(10);
        return view('admin.Product.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $sizes = Size::select('id', 'name')->orderBy('id')->get();
        return view('admin.Product.create-product', compact('categories', 'brands','sizes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $response = $this->productService->storeProduct($request);
        return redirect()->route('admin.products')->with('status', $response['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        // $sizeIds = $product->sizes->pluck('pivot.size_id')->toArray();->whereNotIn('id',$sizeIds)
        $sizes = Size::select('id', 'name')->orderBy('id')->get();

        return view('admin.Product.product-edit', compact(['product', 'categories', 'brands','sizes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request )
    {
        $response = $this->productService->updateProduct($request, $request->id);
        return redirect()->route('admin.products')->with('status', $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->productService->deleteProduct( $id);
        return redirect()->route('admin.products')->with('status', $response['status']);

    }
}
