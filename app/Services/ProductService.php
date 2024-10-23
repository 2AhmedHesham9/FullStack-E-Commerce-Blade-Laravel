<?php

namespace App\Services;

use App\Models\Product;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Intervention\Image\Laravel\Facades\Image;

class ProductService
{

    public function storeProduct(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->slug);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
   
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;
        $product_image = $this->getFileName($request, $current_timestamp);
        $product->image = $product_image;

        $gallery_images =  $this->addImagesInGallery($request, $current_timestamp);
        $product->images = $gallery_images;

        $sizes = $request->input('sizes');
        $quantities = $request->input('quantities');

        $sizeData = [];
        foreach ($sizes as $index => $sizeID) {
            $sizeData[$sizeID] = ['quantity' => $quantities[$index]];
        }

        $product->save();
        $product->sizes()->attach($sizeData);
        return ["status" => "Product has been Added successfully!"];
    }

    public function updateProduct(UpdateProductRequest $request, $id)
    {

        $product = Product::find($id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->slug);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $product_image = $this->getFileName($request, $current_timestamp);
            $product->image = $product_image;
        }

        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as $image) {
                $this->deleteImage(trim($image));
            }
            $gallery_images =  $this->addImagesInGallery($request, $current_timestamp);
            $product->images = $gallery_images;
        }

        $sizes = $request->input('sizes');
        $quantities = $request->input('quantities');

        $sizeData = [];
        foreach ($sizes as $index => $sizeID) {
            $sizeData[$sizeID] = ['quantity' => $quantities[$index]];
        }

        $product->save();
        $product->sizes()->sync($sizeData);
        return ["status" => "Product has been Updated successfully!"];
    }
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        $this->deleteImage($product->image);
        foreach (explode(',', $product->images) as $image) {
            $this->deleteImage(trim($image));
        }
        $product->delete();
        return ["status" => "Product has been deleted successfully!"];
    }
    // delete images from products and thumbnails before adding the new images
    private function deleteImage($productImage)
    {

        if (File::exists(public_path('uploads/products') . '/' . $productImage)) {

            File::delete(public_path('uploads/products') . '/' . $productImage);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $productImage)) {

            File::delete(public_path('uploads/products/thumbnails') . '/' . $productImage);
        }
    }
    // use this to make image ready to use in DB as in stroe product function
    private function getFileName($request, $current_timestamp)
    {
        $file_name = "";
        if ($request->hasFile('image')) {
            $image  = $request->file('image');
            $file_extention =  $request->file('image')->extension();
            $file_name =  $current_timestamp . '.' . $file_extention;
            $this->GenerateProductThumbnailsImage($image, $file_name);
        }
        return $file_name;
    }
    // product can have many images so input accepts multiple images and we store them as text separated by ,
    private function addImagesInGallery($request, $current_timestamp)
    {
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if ($request->hasFile('images')) {
            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextention = $file->getClientOriginalExtension();
                $gcheck = in_array($gextention, $allowedfileExtion);
                if ($gcheck) {
                    $gfileName = $current_timestamp . '-' . $counter . '.' . $gextention;
                    $this->GenerateProductThumbnailsImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        return $gallery_images;
    }

    private function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPath = Public_path('uploads/products');
        $destinationPathThumbnails = Public_path('uploads/products/thumbnails');
        $img = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnails . '/' . $imageName);
    }
}
