@extends('front.layout.app')
@section('title', 'Cart')
@section('content')
    <style>
        input {
            text-align: center;
        }

        .quantity {
            width: 80px;
        }

        .removeItem {
            margin-top: 10px;
        }

        .fa fa-trash {
            color: red !important;
        }

    </style>



    <section class="cartSec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-3 d-flex justify-content-between flex-row flex-wrap">
                    <h2 class="title">Shopping Cart</h2>
                    <!-- <a href="guitar.php" class="orangeBtn-border greyBtn">Continue Shopping</a>
                                                                            <a href="#" class="orangeBtn-border cancelOrder">Cancel Order</a> -->
                </div>
                <div class="col-lg-8">
                    @php $counter = 0;@endphp
                    @forelse ($products as $item)
                        @php $counter++; @endphp
                        <div class="table-responsive">
                            <table class="table table-border">
                                <div class="top">
                                    <h4>Package {{ $counter }} of {{ Cart::count() }}</h4>
                                    <p>{{ $item['shop'] }}</p>
                                </div>
                                {{-- <div class="shipMethod">
                                    <h4>Delivery Option</h4>
                                    <a href="javascript:;" data-toggle="modal" data-target="#shipMethod">Select Shipping
                                        Method</a>
                                </div> --}}
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        {{-- <th>Shipping</th> --}}
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="cell">
                                                <div class="img">
                                                    <img src="{{ productImage($item['product_image']) }}" alt=""
                                                        class="img-fluid">
                                                    <form action="{{ route('cart.destroy', $item['row_id']) }}"
                                                        method="POST" style="display:block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="removeItem">Remove</button>
                                                    </form>
                                                </div>
                                                <div class="content">
                                                    <h4>{{ $item['name'] }}</h4>
                                                    <span>{{ $item['category'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ presentPrice($item['price']) }}</td>
                                        <td>
                                            <div class="shop-details">
                                                <input type="text" name="quantity" class="form-control input-number pl-0"
                                                    value="{{ $item['quantity'] }}" min="1" max="100">
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="quantity quantity-right-plus btn btn-number" data-type="plus"
                                                        data-field="" data-id="{{ $item['row_id'] }}"
                                                        data-qty="{{ $item['quantity'] }}"
                                                        data-productQuantity="{{ $item['productQuantity'] }}">
                                                        <span class="glyphicon glyphicon-plus">
                                                            <svg class="svg-inline--fa fa-angle-up fa-w-10"
                                                                aria-hidden="true" data-prefix="fas" data-icon="angle-up"
                                                                role="img" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 320 512" data-fa-i2svg="">
                                                                <path fill="currentColor"
                                                                    d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z">
                                                                </path>
                                                            </svg><!-- <i class="fas fa-angle-up"></i> --></span>
                                                    </button>
                                                    <button type="button" data-qty="{{ $item['quantity'] }}"
                                                        class="quantity quantity-left-minus btn btn-number"
                                                        data-id="{{ $item['row_id'] }}" data-type="minus" data-field=""
                                                        data-productQuantity="{{ $item['productQuantity'] }}">
                                                        <svg class="svg-inline--fa fa-angle-down fa-w-10" aria-hidden="true"
                                                            data-prefix="fas" data-icon="angle-down" role="img"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                                                            data-fa-i2svg="">
                                                            <path fill="currentColor"
                                                                d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z">
                                                            </path>
                                                        </svg><!-- <i class="fas fa-angle-down"></i> -->
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                        {{-- <td>$169.99</td> --}}
                                        <td>{{ presentPrice($item['subtotal']) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    @empty
                        <div class="table-responsive">
                            <table class="table table-border">
                                <div class="top">
                                    {{-- <h4>Package {{ $counter }} of {{ Cart::count() }}</h4> --}}
                                    <p></p>
                                </div>
                                {{-- <div class="shipMethod">
                                    <h4>Delivery Option</h4>
                                    <a href="javascript:;" data-toggle="modal" data-target="#shipMethod">Select Shipping
                                        Method</a>
                                </div> --}}
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        {{-- <th>Shipping</th> --}}
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>No Product in Cart!</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    @endforelse

                </div>
                <div class="col-lg-4">
                    <div class="orderSumry">
                        <div class="head">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="summary-body">
                            <p>{{ Cart::count() }} Items in your Cart</p>
                            <strong>Subtotal <span>{{ presentPrice(Cart::subtotal()) }}</span></strong>
                            <strong>Shipping <span>Calculated at checkout</span></strong>
                            {{-- <strong>Tax <span>Calculated at checkout</span></strong> --}}
                        </div>
                        <div class="summary-footer">
                            <strong>Total <span>{{ presentPrice(Cart::total()) }}</span></strong>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="orangeBtn mt-5 d-block text-center">Checkout</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-js')

    <script>
        (function() {
            $(".quantity").on('click', function() {


            })
        })();
        // Script for Counter
        $(document).ready(function() {
            var quantitiy = 0;
            $('.quantity-right-plus').click(function(e) {
                e.preventDefault();

                quantity = parseInt($(this).parent().prev().val());
                $(this).parent().prev().val(quantity + 1);

                const id = $(this).data('id');
                productQuantity = $(this).data('productquantity');
                quantity = parseInt($(this).parent().prev().val());
                console.log(productQuantity);
                $.ajax({
                    url: `cart/update/${id}`,
                    type: 'PATCH',
                    data: {
                        quantity: $('input[name="quantity"]').val(),
                        productQuantity: productQuantity,
                        "_token": $("meta[name='csrf-token']").attr('content')
                    },
                    success: function() {
                        setTimeout(window.location.reload(), 2000);
                    },
                    error: function() {
                        window.location.reload();
                    }
                })
            });

            $('.quantity-left-minus').click(function(e) {
                e.preventDefault();
                quantitiy = $(this).data('qty');
                quantity = parseInt($(this).parent().prev().val());
                productQuantity = $(this).data('productquantity');
                if (quantity > 0) {
                    $(this).parent().prev().val(quantity - 1);
                    const id = $(this).data('id');
                    $.ajax({
                        url: `cart/update/${id}`,
                        type: 'PATCH',
                        data: {
                            quantity: $('input[name="quantity"]').val(),
                            productQuantity: productQuantity,
                            "_token": $("meta[name='csrf-token']").attr('content')
                        },
                        success: function() {
                            setTimeout(window.location.reload(), 1000);
                        },
                        error: function() {
                            window.location.reload();
                        }
                    })
                }
            });
        });
    </script>
@endsection
