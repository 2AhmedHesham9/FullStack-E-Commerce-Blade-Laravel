<?php

namespace App\Http\Controllers;

use App\Models\Brand;

use Illuminate\Http\Request;
use App\Services\BrandService;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;

class BrandController extends Controller
{
    protected $brandService;
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->brandService->showBrands() ;
        $brands = $response['brands'];
        return view('admin.Brand.brands', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Brand.create-brand');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBrandRequest $request)
    {
        $response =   $this->brandService->storeBrand($request);
        return redirect()->route('admin.brands')->with('status', $response['status']);
    }



    /**
     * Display the specified resource.
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.Brand.brand-edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request)
    {
        $response =   $this->brandService->updateBrand($request,$request->id);
        return redirect()->route('admin.brands')->with('status', $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $response = $this->brandService->deleteBrand($id);
        return redirect()->route('admin.brands')->with('status', $response['status']);

    }
}
