<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Cart::restore(Auth::id());
        Cart::instance('wishlist')->restore(Auth::id());
        $items = Cart::instance('wishlist')->content();

        return view('wishlist', compact('items'));
    }

    public function addToWishlist(Request $request)
    {
        // Cart::instance('cart')->destroy();
        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        $this->store();
        return redirect()->back();
    }

    public function store()
    {
        Cart::store(Auth::id());
        Cart::instance('wishlist')->store(Auth::id());
    }

    public function removeItem($rowId) //remove from cart list
    {
        Cart::instance('wishlist')->remove($rowId);

        $this->store();

        return redirect()->back();
    }

    public function emptyWishlist()
    {
        Cart::instance('wishlist')->destroy();
        $this->store();
        return  redirect()->back();
    }
    public function copyToCart($rowId, Request $request)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        $proqty = explode(',', $request->size)[0];
        $size = explode(',', $request->size)[1];
        Cart::instance('cart')->add(
            [

                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' =>  $item->price,
                'options' => [
                    'size' => $size,
                    'proqty' => $proqty

                ]
            ]
        )->associate('App\Models\Product');
        $this->store();
        return  redirect()->back();
    }
}
