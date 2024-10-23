<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categoryResponse = $this->categoryService->showallcategory();
        $categories = $categoryResponse['categories'];
        $randomCategories = Category::with(['products' => function ($query) {
            $query->whereNotNull('sale_price');
        }])->has('products')->inRandomOrder()->get()->take(2);

        $saleProducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);
        $maxSale = DB::Select("SELECT   100 - round((sale_price) *100/ regular_price ) maxsale FROM `products`
                                where sale_price is Not NULL
                                ORDER by maxsale desc
                                limit 1");
        if ($maxSale) {

            $maxSale = $maxSale[0];
        } else {
            $maxSale = $maxSale;
        }
        $featuredProducts = Product::Where('featured', 1)->inRandomOrder()->get()->take(8);
        $slides = Slide::Where('status', 1)->get()->take(3);


        return view('index', compact('slides', 'categories', 'saleProducts', 'maxSale', 'featuredProducts', 'randomCategories'));
    }
    public function contact()
    {
        return view('contact');
    }


    public function about()
    {
        return view('about');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $result = Product::with('category', 'brand')->where('name', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($cat) use ($query) {
                $cat->where('name', 'LIKE', "%{$query}%");
            })->orWhereHas('brand', function ($cat) use ($query) {
                $cat->where('name', 'LIKE', "%{$query}%");
            })->get()->take(8);
        return response()->json($result);
    }
}
