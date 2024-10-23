<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Services\CouponService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
        return view('admin.Coupon.coupons', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.Coupon.create-coupon');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        $response = $this->couponService->storeCoupon($request);

        return redirect()->route('admin.coupons')->with('status', $response['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrfail($id);
        return view('admin.Coupon.coupon-edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request)
    {
        $response = $this->couponService->updateCoupon($request);
        return redirect()->route('admin.coupons')->with('status', $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->couponService->deleteCoupon($id);
        return redirect()->route('admin.coupons')->with('status', $response['status']);
    
    }
}
