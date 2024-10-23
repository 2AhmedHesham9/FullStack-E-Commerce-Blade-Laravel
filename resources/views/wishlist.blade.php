@extends('layouts.app')
<style>
    .shopping-cart .cart-table tbody td {
        padding: 3px !important;
        display: table-cell;
        margin: 0;
        align-items: center
    }
</style>
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">Wishlist</h2>

        <div class="shopping-cart">
            @if(Cart::instance('wishlist')->content()->count()>0)
            <div class="cart-table__wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th></th>
                            <th>Price</th>

                            <th>Quantity</th>
                            <th></th>

                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)

                        <tr>
                            <td>
                                <div class="shopping-cart__product-item">
                                    <img loading="lazy"
                                        src="{{ asset('uploads/products/thumbnails') }}/{{ $item->model->image }}"
                                        width="120" height="120" alt="{{ $item->name }}" />
                                </div>
                            </td>
                            <td>
                                <div class="shopping-cart__product-item__detail">
                                    <h4>{{ $item->name }}</h4>
                                    {{-- <ul class="shopping-cart__product-item__options">
                                        <li>Color: Yellow</li>
                                        <li>Size: L</li>
                                    </ul> --}}
                                </div>
                            </td>
                            <td>

                                <span class="shopping-cart__product-item__detail">${{ $item->price }} </span>
                            </td>
                            <td>
                                {{ $item->qty }}
                            </td>


                            <td>
                                <div class="row" style="margin-top:25px">
                                    <div class="col-6">

                                        @if(Cart::instance('cart')->content()->where('id', '=', $item->id)->count() > 0)
                                        <a href="{{ route('cart.index') }}" class="btn btn-sm btn-warning rounded-1">Go
                                            To Cart</a>
                                        @else
                                        <form name="addtocart-form" method="post"
                                            action="{{ route('wishlist.move',['rowId'=>$item->rowId]) }}">
                                            @csrf
                                            <table class="cart-table     ">

                                                <tbody>

                                                    <tr>
                                                        <td>

                                                            <div class="select">
                                                                <select name="size" id="option-select" required
                                                                    class=" select">
                                                                    {{-- <option value="">Select Size</option> --}}
                                                                    {{ $item->size}}
                                                                    @foreach ($item->model->sizes as $size )
                                                                    @if($size->pivot->quantity >0)

                                                                    <option
                                                                        value="{{ $size->pivot->quantity . ',' . $size->name   }}  ">
                                                                        {{ $size->name }}
                                                                    </option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="product-single__addtocart">
                                                                <button type="submit"
                                                                    class="btn btn-primary btn-sm rounded-1 "
                                                                    data-aside="cartDrawer">Move
                                                                    to
                                                                    Cart</button>
                                                            </div>
                                                        </td>

                                                    </tr>

                                                </tbody>
                                            </table>

                                        </form>

                                        @endif
                                    </div>
                            </td>
                            <td>
                                <div class="col-6">
                                    <form action="{{ route('wishlist.item.remove',['rowId'=>$item->rowId]) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')

                                        <a href="javascript::void(0)" class="remove-cart">
                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                <path
                                                    d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                            </svg>
                                        </a>
                                    </form>
                                </div>
            </div>
            </td>
            </tr>
            @endforeach

            </tbody>
            </table>
            <div class="cart-table-footer">
                <form action="{{ route('wishlist.empty') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-light" type="submit">CLEAR WISHLIST</button>
                </form>
            </div>
        </div>

        @else
        <div class="row">
            <div class="col-md-12 text-center pt-5 bp-5">
                <p>No item found in your Wishlist</p>
                <a href="{{ route('shop.index') }}" class="btn btn-info">Wishlist Now</a>
            </div>
        </div>
        @endif
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(function() {

            $(".qty-control__increase").on("click", function(){

                $(this).closest('form').submit();
            });
            $(".qty-control__reduce").on("click", function(){
                $(this).closest('form').submit();
            });
            $('.remove-cart').on("click", function(){
                $(this).closest('form').submit();
            });
        })
</script>

@endpush