<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\shoppingcart;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Carbon;
use App\Services\AddressService;
use App\Services\OrderItemService;
use Illuminate\Support\Facades\DB;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    protected $orderItemService;
    protected $transactionSercvice;
    protected $addressService;
    protected $orderService;
    public function __construct(OrderService $orderService, OrderItemService $orderItemService, TransactionService $transactionSercvice,  AddressService $addressService)
    {

        $this->orderItemService = $orderItemService;
        $this->transactionSercvice = $transactionSercvice;
        $this->addressService = $addressService;
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() // all orders for admin
    {
        $response = $this->orderService->get_Orders_For_Admin();
        $orders =  $response['orders'];
        return view('admin.Order.orders', compact('orders'));
    }
    public function adminOrderDetails($id) //admin
    {
        $response = $this->orderService->get_Order_Details_For_Admin($id);
        $order =  $response['orders'];
        $orderItems =  $response['orderItems'];
        $transaction =  $response['transaction'];
        return view('admin.Order.order-details', compact('orderItems', 'order', 'transaction'));
    }
    public function userOrders() //user
    {
        $response = $this->orderService->get_Orders_For_User();
        $orders =  $response['orders'];
        return view('user.orders', compact('orders'));
    }
    public function userOrderDetails($id) //user
    {
        $response = $this->orderService->get_Order_Details_For_User($id);
        $order =  $response['orders'];
        $orderItems =  $response['orderItems'];
        $transaction =  $response['transaction'];
        return view('user.order-details', compact('orderItems', 'order', 'transaction'));
    }
    public function usercancleorder(Request $request) //user
    {
        $response = $this->orderService->user_Cancel_Order($request);
        return  back()->with('status', $response['status']);
    }


    public function orderConfirmation()
    {
        if (session('order_id')) {
            $order = Order::find(session('order_id'));

            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            // $address = Address::with('user')->where('user_id', $user->id)->where('isdefault', 1)->first();

            //  start save the address
            $response = $this->addressService->Store_Order_Address($request, $user->id);
            $address = $response['address'];
            // End Of saving Address

            // open session to save in checkout
            $this->setAmountforCheckout();
            // end session to save in checkout

            // start stor order
            $order = new Order();
            $order->user_id = $user->id;
            $order->subtotal = Session('checkout')['subtotal'];
            $order->tax = Session('checkout')['tax'];
            $order->discount = Session('checkout')['discount'];
            $order->total = Session('checkout')['total'];
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->landmark = $address->landmark;
            $order->zip = $address->zip;
            $order->save();
            // end of store order


            // start store product into cartitems
            $this->orderItemService->Store_Order_Items($order->id);

            // end of storing product into cartitems

            // start transaction
            $this->transactionSercvice->store_order_Transaction($request->mode, $user->id, $order->id);
            // end transaction
            $this->destroySessions();

            shoppingcart::where('identifier', $user->id)->where('instance', 'cart')->delete();
            Session::put('order_id', $order->id);
            DB::commit();
            return redirect()->route('cart.order.confirmation');
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    public function setAmountforCheckout()
    {
        if (!cart::instance('cart')->content()->count() > 0) {
            Session::forget('cart');
            return;
        }
        if (session('coupon')) {
            Session::put(
                'checkout',
                [
                    'subtotal' => Session('discounts')['subtotal'],
                    'tax' => Session('discounts')['tax'],
                    'discount' => Session('discounts')['discount'],
                    'total' => Session('discounts')['total']
                ]
            );
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }
    }
    public function destroySessions()
    {
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updateOrderStatus(Request $request)
    {
        $response = $this->orderService->Admin_Change_Order_Status($request);
        return  back()->with('status', $response['status']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
