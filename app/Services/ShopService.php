<?php

namespace App\Services;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\CategoryService;


class ShopService
{
    protected $brandService;
    protected $categoryService;
    public function __construct(BrandService $brandService, CategoryService $categoryService)
    {
        $this->brandService = $brandService;
        $this->categoryService = $categoryService;
    }
    public function ShowProducts(Request $request)
    {
        $size = $request->query->get('size') ? $request->query('size') : 12;
        $order = $request->query->get('order') ? $request->query('order') : 'def';
        $f_brands = $request->query->get('brands');
        $f_categories = $request->query->get('categories');
        $min_product_price = Product::min('sale_price');
        $max_product_price = Product::max('regular_price');
        $min_price = $request->query->get('min') ? $request->query('min') : $min_product_price;
        $max_price = $request->query->get('max') ? $request->query('max') : $max_product_price;

        $showorder = $this->OrderProductsBY($order);
        $productSizes = Size::orderBy('id', 'Asc')->get();
        $f_product_size = $request->query->get('product_sizes');
        $products = Product::whereHas(
            'sizes',
            function ($query) use ($f_product_size) {
                if ($f_product_size) {
                    $query->where('name', $f_product_size);
                }
            }
        )
            ->where(
                function ($query) use ($f_brands, $f_categories) {
                    if (!empty($f_brands)) {
                        $brandIds = explode(',', $f_brands);
                        $query->whereIn('brand_id', $brandIds);
                        // ->orwhereRaw("'".$f_brands."'");
                    }
                    if (!empty($f_categories)) {
                        $categoryIds = explode(',', $f_categories);
                        $query->whereIn('category_id', $categoryIds);
                    } else {
                        $query->orwhereRaw('1 = 1');
                    }
                }
            )
            ->where(function ($query) use ($min_price, $max_price) {
                $query->whereBetween('regular_price', [$min_price, $max_price])->orWhereBetween('sale_price', [$min_price, $max_price]);
            })
            ->orderBy($showorder['columnName'], $showorder['order'])->paginate($size);

        $brandresponse = $this->brandService->showAllBrands();
        $categoryresponse = $this->categoryService->showallcategory();
        $brands = $brandresponse['brands'];
        $categories = $categoryresponse['categories'];
        return [
            "size" => $size,
            "order" => $order,
            "products" => $products,
            "brands" => $brands,
            "f_brands" => $f_brands,
            "categories" => $categories,
            "f_categories" => $f_categories,
            "min_price" => $min_price,
            "max_price" => $max_price,
            'min_product_price' => $min_product_price,
            'max_product_price' => $max_product_price,
            'f_product_size' => $f_product_size,
            'productSizes' => $productSizes
        ];
    }


    private function OrderProductsBY($order)
    {
        $order_column = '';
        $o_order = '';
        switch ($order) {

            case 1:
                $order_column = "sale_price";
                $o_order = 'ASC';
                break;
            case 2:
                $order_column = "sale_price";
                $o_order = 'DESC';
                break;
            case 3:
                $order_column = "created_at";
                $o_order = 'ASC';
                break;
            case 4:
                $order_column = "created_at";
                $o_order = 'DESC';
                break;
            default:
                $order_column = "id";
                $o_order = 'DESC';
        }
        return ["columnName" => $order_column, "order" => $o_order];
    }
}
