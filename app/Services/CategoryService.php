<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Intervention\Image\Laravel\Facades\Image;

class CategoryService {
    public function showallcategory()
    {
        $categories=Category::orderBy('name', 'ASC')->get();
        return ['categories'=>$categories];
    }
    public function storeCategory(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $image  = $request->file('image');
        $file_extention =  $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;

        $this->GenerateCategoryThumbnailsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();
        return ["status" => "Category has been added successfully!"];
    }
    public function updateCategory(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $image  = $request->file('image');
            $file_extention =  $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->deleteImage($category->image);
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return ["status" => "Category has been updated successfully!"];
    }
    public function deleteCategory($id){
        $category = Category::find($id);
        $this->deleteImage($category->image);
        $category->delete();
        return ["status" => "Category has been deleted successfully!"];
    }
    private function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = Public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }
    private function deleteImage($brandImage)
    {

        if (File::exists(public_path('uploads/categories') . '/' . $brandImage)) {

            File::delete(public_path('uploads/categories') . '/' . $brandImage);
        }
    }
}
