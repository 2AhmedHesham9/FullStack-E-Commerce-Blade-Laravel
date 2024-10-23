<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Services\OrderItemService;
use App\Http\Requests\StoreOrderItemRequest;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Http\Requests\UpdateOrderItemRequest;

class OrderItemController extends Controller
{
    protected $orderItemService;
    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store($order_id)
    {
        $this->orderItemService->Store_Order_Items($order_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        //
    }
}
