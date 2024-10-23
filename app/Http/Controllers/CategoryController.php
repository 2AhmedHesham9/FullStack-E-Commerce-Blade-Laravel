<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
 protected $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.Category.categories', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Category.create-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $response =   $this->categoryService->storeCategory($request);
        return redirect()->route('admin.categories')->with('status', $response['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category =Category::find($id);
         return view('admin.Category.category-edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request)
    {
        $response =   $this->categoryService->updateCategory($request,$request->id);
        return redirect()->route('admin.categories')->with('status', $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->categoryService->deleteCategory($id);
        return redirect()->route('admin.categories')->with('status', $response['status']);

    }
}
