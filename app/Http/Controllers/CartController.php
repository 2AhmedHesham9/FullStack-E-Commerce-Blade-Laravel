<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Cart::instance('cart')->content();
        // dd($items);
        return view('cart', compact('items'));
    }


    // To store a cart instance named 'wishlist'


    public function addToCart(Request $request)
    {
        $proqty=explode(',', $request->size)[0];
        $size = explode(',', $request->size)[1];
        // Cart::instance('cart')->destroy();
        Cart::instance('cart')->add(
            [
                'id' => $request->id,                  // Product ID
                'name' => $request->name,              // Product Name
                'qty' => $request->quantity,            // Quantity
                'price' => $request->price,             // Price
                'options' => [
                    'size' => $size,
                    'proqty'=>$proqty
                ]
            ]
        )->associate('App\Models\Product');
        $this->store();
        return redirect()->back();
    }

    public function increaseCartQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $proId = intval($product->id);
        // $quantity = DB::select("SELECT quantity FROM products Where id = '$proId'");
        // $quantity = $quantity[0]->quantity;
        // dd($product->qty <= $quantity);

        if ($product->qty < $product->options->proqty) {
            $qty = $product->qty + 1;
            Cart::instance('cart')->update($rowId, $qty);
            $this->store();
        }
        return redirect()->back();
    }
    public function decreaseCartQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        if ($product->qty > 1) {

            $qty = $product->qty - 1;
            Cart::instance('cart')->update($rowId, $qty);
            $this->store();
        }
        return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        Cart::store(Auth::id());
        Cart::instance('cart')->store(Auth::id());
    }
    public function storeWishlist()
    {
        Cart::store(Auth::id());
        Cart::instance('wishlist')->store(Auth::id());
    }
    public function removeItem($rowId) //remove from cart list
    {
        Cart::instance('cart')->remove($rowId);

        $this->store();

        return redirect()->back();
    }
    public function emptyCart()
    {
        Cart::instance('cart')->destroy();
        $this->store();

        return  redirect()->back();
    }
    public function applyCouponCode(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', str_replace(',', '', Cart::instance('cart')->subtotal()))->first();
            if (!$coupon) {
                return redirect()->back()->with('error', 'Invalid coupon covvde');
            } else {

                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Coupon has been applied');
            }
        }
        return redirect()->back()->with('error', 'Invalid coupon code');
    }
    private function calculateDiscount()
    {
        $discount = 0;
        $subtotal = Cart::instance('cart')->subtotal();

        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discountValue = Session::get('coupon')['value'];
                $discount = ((str_replace(',', '', $subtotal) * $discountValue) / 100);
            }
            $subtotalAfterDiscount =  str_replace(',', '', $subtotal)  - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;
            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
                'total' => number_format(floatval($totalAfterDiscount), 2, '.', ''),
            ]);
        }
    }
    public function checkout()
    {
        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
