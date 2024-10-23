<?php

namespace App\Services;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class OrderItemService
{
    public function Store_Order_Items($order_id)
    {

        $orderItems = array_map(
            fn($item)   => [
                'product_id' => $item['id'],
                'order_id' => $order_id,
                'price' => $item['price'],

                'size' => $item['options']['size'],
                'quantity' => $item['options']['proqty'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            Cart::instance('cart')->content()->toArray()
        );
        OrderItem::insert($orderItems);
        $this->updateProductQuantity($orderItems);
    }

    private function updateProductQuantity($products)
    {
        DB::transaction(function () use ($products) {
            foreach ($products as $product) {

                $proId = intval($product['product_id']);
                $prosize = $product['size'];

                $quantity = DB::select(" Select ps.id,quantity from product_sizes ps join sizes s on s.id= ps.size_id  Where product_id = '$proId' AND s.name = '$prosize'  ");

                $prosizeId = $quantity[0]->id;



                DB::table('product_sizes')
                    ->where('id', $prosizeId)  // Match the product by its ID
                    ->update(['quantity' => ($quantity[0]->quantity - intval($product['quantity']))]);  // Update the price
            }
        });
    }
}
