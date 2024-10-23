<?php

namespace App\Services;

use App\Models\Slide;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use Intervention\Image\Laravel\Facades\Image;

class SlideService
{

    public function get_Slide_For_Admin()
    {
        $slides = Slide::orderBy('id', 'DESC')->paginate(12);
        return ['slides' => $slides];
    }

    public function store_slide(StoreSlideRequest $request)
    {
        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        $image = $request->file('image');
        $file_extention = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;

        $this->GenerateSlideThumbnailsImage($image, $file_name);
        $slide->image = $file_name;


        $slide->save();

        return ['status' => 'Slide has been added successfully!'];
    }
    public function edit_Slide($id)
    {
        $slide = Slide::findOrfail($id);
        return ['slide' => $slide];
    }
    public function update_Slide(UpdateSlideRequest $request)
    {
        $slide = Slide::findOrfail($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->hasFile('image')) {
            $this->deleteImage($slide->image);
            $image = $request->file('image');
            $file_extention = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateSlideThumbnailsImage($image, $file_name);
            $slide->image = $file_name;
        }
        $slide->save();
        return ['status' => 'Slide has been updated successfully!'];
    }

    public function delete_slide($id)
    {
        $slide = Slide::findOrfail($id);
        $this->deleteImage($slide->image);
        $slide->delete();
        return ['status' => 'Slide has been deleted successfully!'];
    }

    private function GenerateSlideThumbnailsImage($image, $imageName)
    {
        $destinationPath = Public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400, 690, "top");
        $img->resize(400, 690, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }
    private function deleteImage($slidedImage)
    {

        if (File::exists(public_path('uploads/slides') . '/' . $slidedImage)) {

            File::delete(public_path('uploads/slides') . '/' . $slidedImage);
        }
    }
}
