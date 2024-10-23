<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Services\SlideService;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;

class SlideController extends Controller
{
    protected $slideService;
    public function __construct(SlideService $slideService)
    {
        $this->slideService = $slideService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->slideService->get_Slide_For_Admin();
        $slides = $response['slides'];
        return view('admin.Slide.slides', compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Slide.create-slide');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSlideRequest $request)
    {
        $response = $this->slideService->store_slide($request);
        return redirect()->route('admin.slides')->with('status', $response['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slide $slide)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $response = $this->slideService->edit_Slide($id);
        $slide = $response['slide'];
        return view('admin.Slide.slide-edit', compact('slide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSlideRequest $request)
    {
        $response = $this->slideService->update_Slide($request);
        return redirect()->route('admin.slides')->with('status', $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->slideService->delete_slide($id);
        return redirect()->route('admin.slides')->with('status', $response['status']);
    }
}
