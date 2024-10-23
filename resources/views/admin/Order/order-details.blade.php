@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }

    .table-striped th:nth-child(1),
    .table-striped td:nth-child(1) {
        width: 150px;
        /* padding-bottom: 18px; */

    }

    .table-striped th:nth-child(3),
    .table-striped td:nth-child(3) {
        width: 150px;
        /* padding-bottom: 18px; */

    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order Items</div>
                </li>
            </ul>
        </div>
        <div class="wg-box mt-5">
            <h5>Update Status</h5>
            @session('status')
            <p class="alert alert-success">{{ session('status') }}</p>
            @endsession
            <form action="{{ route('admin.order.status.update') }}" method="POST">
                @csrf
                @method('put')
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">

                            <select name="order_status" id="order_status" class="text-center">
                                <option value="orderd" @selected($order->status == 'orderd') >Orderd</option>
                                <option value="delivered" @selected($order->status == 'delivered') >Delivered</option>
                                <option value="canceled" @selected($order->status == 'canceled') >Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Update Status</button>
                    </div>
                </div>

            </form>


        </div>


        <div class="wg-box mt-5">

            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Details</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Back</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Order No</th>
                            <td class="text-center">{{ $order->id }}</td>
                            <th>Mobile</th>
                            <td class="text-center">{{ $order->phone }}</td>
                            <th>Zip</th>
                            <td class="text-center">{{ $order->zip }}</td>
                        </tr>

                        <tr>
                            <th>Order Date</th>
                            <td class="text-center">{{ $order->created_at }}</td>
                            <th>Delivered Date</th>
                            <td class="text-center ">{{ $order->delivery_date }}</td>
                            <th>Canceled Date</th>
                            <td class="text-center">{{ $order->canceled_date }}</td>
                        </tr>
                        <tr>
                            <th>Order Status</th>
                            <td colspan="5">
                                @if( $order->status =="orderd")
                                <span class="badge bg-warning fs-4">Orderd </span>
                                @elseif( $order->status == 'delivered')
                                <span class="badge bg-success fs-4">Delivered</span>
                                @else
                                <span class=" badge bg-danger fs-4">Canceled</span>
                                @endif
                            </td>


                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Size</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Options</th>
                            <th class="text-center">Return Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $orderItem)

                        <tr>

                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{  $orderItem->product->image }}"
                                        alt="{{ $orderItem->product->image }}" class="image">
                                </div>
                                <div class="name">
                                    <a href="#" target="_blank" class="body-title-2">{{ $orderItem->product->name }}</a>
                                </div>
                            </td>
                            <td class="text-center">${{ $orderItem->price }}</td>
                            <td class="text-center">{{ $orderItem->quantity }}</td>
                            <td class="text-center">{{ $orderItem->size }}</td>
                            <td class="text-center">{{ $orderItem->product->SKU }}</td>
                            <td class="text-center"> {{ $orderItem->product->category->name }}</td>
                            <td class="text-center"> {{ $orderItem->product->brand->name }}</td>
                            <td class="text-center">{{ $orderItem->options }}</td>
                            <td class="text-center">{{ $orderItem->rstatus == false ? "No":"Yes" }}</td>
                            <td class="text-center">
                                <div class="list-icon-function view-icon">
                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p><b>Name:</b> {{ $order->name }}</p>
                    <p><b>Address:</b> {{ $order->address }}</p>
                    <p><b>Locality: </b> {{ $order->locality }}</p>
                    <p><b>City|Country: </b>{{ $order->city }}, {{ $order->country }} </p>
                    <p><b>LandMark:</b> {{ $order->landmark}}</p>
                    <p><b>Zip:</b> {{ $order->zip }}</p>
                    <br>
                    <p><b>Mobile: </b> {{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transactions</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>${{ $order->subtotal }}</td>
                            <th>Tax</th>
                            <td>${{ $order->tax }}</td>
                            <th>Discount</th>
                            <td>${{ $order->discount }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>${{ $order->total }}</td>
                            <th>Payment Mode</th>
                            <td>{{ $transaction->mode }}</td>
                            <th>Status</th>
                            <td>
                                @if($transaction->status== 'approved')

                                <span class="badge bg-success fs-5">Approved</span>
                                @elseif($transaction->status== 'declineded')
                                <span class="badge bg-danger fs-5">Declined</span>
                                @elseif($transaction->status== 'refunded')
                                <span class="badge bg-secondary fs-5">Refunded</span>
                                @else
                                <span class="badge bg-warning fs-5">Pending</span>
                                @endif

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>




</div>
@endsection
