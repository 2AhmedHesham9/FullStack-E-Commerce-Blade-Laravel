<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;

class CouponService
{

    public function storeCoupon(StoreCouponRequest $request)
    {
        $coupon = new Coupon();

        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return ['status' => "Coupon has been added successfully!"];
    }
    public function updateCoupon(UpdateCouponRequest $request)
    {
        $coupon = Coupon::findOrfail($request->id);

        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return ['status' => "Coupon has been updated successfully!"];
    }
    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrfail($id);
        $coupon->delete();
        Session::forget('coupon');
        Session::forget('discounts');
        return ['status' => "Coupon has been deleted successfully!"];
    }
}
