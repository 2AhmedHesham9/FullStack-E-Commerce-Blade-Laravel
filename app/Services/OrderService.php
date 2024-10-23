<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;



class OrderService
{

    // methodes for Admin

    public function get_Orders_For_Admin()
    {
        $orders = Order::orderby('created_at', 'desc')->paginate(12);
        return ['orders' => $orders];
    }
    public function get_Order_Details_For_Admin($id)
    {
        $order = Order::findOrfail($id);
        $orderItems = OrderItem::where('order_id', $id)->orderBy('price', 'desc')->get();
        $transaction = Transaction::where('order_id', $id)->first();
        return ['orders' => $order, 'orderItems' => $orderItems, 'transaction' => $transaction];
    }
    public function Admin_Change_Order_Status(Request $request)
    {
        $order = Order::findOrfail($request->order_id);
        $order->status = $request->order_status;

        if ($request->order_status == 'delivered') {
            $order->delivery_date = Carbon::now();
        }
        elseif ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();
        }
        $order->save();
        $this->change_Transaction_to_approved($request->order_status, $request->order_id);
        return ['status' => 'status updated successfully!'];
    }
    private function change_Transaction_to_approved($order_status, $order_id)
    {
        if ($order_status == 'delivered') {
            $transaction = transaction::where('order_id', $order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
    }

    // Methods for user
    public function get_Orders_For_User()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderby('created_at', 'DESC')->paginate(10);
        return ['orders' => $orders];
    }

    public function get_Order_Details_For_User($id)
    {
        $order = Order::findOrfail($id);
        $orderItems = OrderItem::where('order_id', $id)->orderBy('price', 'desc')->get();
        $transaction = Transaction::where('order_id', $id)->first();
        return ['orders' => $order, 'orderItems' => $orderItems, 'transaction' => $transaction];
    }
    public function user_Cancel_Order(Request $request)
    {
        $order = Order::findOrfail($request->order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();
        $this->change_Transaction_to_declined($request->order_id);

        return ['status' => 'Order has been  Cancelled successfully!'];
    }
    private function change_Transaction_to_declined($order_id)
    {
        $transaction = transaction::where('order_id', $order_id)->first();
        $transaction->status = 'declined';
        $transaction->save();
    }
}
