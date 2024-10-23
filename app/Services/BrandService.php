<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;

class BrandService
{
    public function showBrands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return ['brands' => $brands];
    }
    public function showAllBrands()
    {
        $brands = Brand::orderBy('name', 'ASC')->get();
        return ['brands' => $brands];
    }
    public function storeBrand(CreateBrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);
        $image  = $request->file('image');
        $file_extention =  $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;

        $this->GenerateBrandThumbnailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return ["status" => "Brand has been added successfully!"];
    }
    public function updateBrand(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $image  = $request->file('image');
            $file_extention =  $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->deleteImage($brand->image);
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return ["status" => "Brand has been updated successfully!"];
    }
    public function deleteBrand($id){
        $brand = Brand::find($id);
        $this->deleteImage($brand->image);
        $brand->delete();
        return ["status" => "Brand has been deleted successfully!"];
    }
    private function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = Public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }
    private function deleteImage($brandImage)
    {

        if (File::exists(public_path('uploads/brands') . '/' . $brandImage)) {

            File::delete(public_path('uploads/brands') . '/' . $brandImage);
        }
    }
}
