@extends('front.layout.app')
@section('title', 'Checkout')
@section('content')
    <style>
        .invalid-feedback {
            margin-top: -20px;
        }

        input.invalid {
            background-color: #ffe5e5 !important;
            border-color: red !important;
        }

        select.invalid {
            background-color: #ffe5e5 !important;
            border-color: red !important;
        }

        textarea.invalid {
            background-color: #ffe5e5 !important;
            border-color: red !important;
        }

        .paymentBtn {
            background-color: #5f5fff;
            border-radius: 28px;
            border-style: none
        }

        #strip_btn {
            background-color: #3838fb;
            border-radius: 3px;
            border-style: none;
            width: 100%;
            height: 45px;
            color: white;
        }

        #split_btn {
            background-color: #3838fb;
            border-radius: 3px;
            border-style: none;
            width: 100%;
            height: 45px;
            color: white;
        }

        #overlay {
            background: #ffffff;
            color: #666666;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            float: left;
            text-align: center;
            padding-top: 25%;
            opacity: .80;
        }

        .spinner {
            margin: 0 auto;
            height: 64px;
            width: 64px;
            animation: rotate 0.8s infinite linear;
            border: 5px solid firebrick;
            border-right-color: transparent;
            border-radius: 50%;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

    </style>
    <section class="cartSec checkoutSec">
        <div id="overlay" class="loading" style="display:none">
            <div class="spinner"></div>
            <h5>Please Wait!</h5>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title">Checkout</h2>
                </div>
                <div class="col-lg-8">
                    <div class="accordion accordioinStyle" id="accordionExample">
                        @if (!Auth::check())
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#loginCollapse" aria-expanded="true" aria-controls="loginCollapse">
                                        Login
                                    </button>
                                </div>

                                <div id="loginCollapse" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="{{ url('login') }}" class="orangeBtn">Login</a>
                                        <a href="{{ url('register') }}" class="orangeBtn">Create Account</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#billingCollapse" aria-expanded="false" aria-controls="billingCollapse">
                                    Billing and Shipping Address
                                </button>
                            </div>
                            <div id="billingCollapse" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    @if (Auth::check() && (Auth::user()->role_id = 3))
                                        <form action="" class="row formStyle billingForm" method="POST">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="First Name" name="first_name"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->first_name }} @endif" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="Last Name" name="last_name"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->last_name }} @endif" required>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control"
                                                    placeholder="Company Name(Optional)" name="company_name"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->company_name }} @endif">
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="Street Line" name="address1"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->address1 }} @endif" required>
                                            </div>
                                            {{-- <div class="col-md-12">
                                                <input type="text" class="form-control requiredField" name="address2"
                                                    placeholder="Street Line" value="@if (!empty($shippingAddress)) {{ $shippingAddress->address2 }} @endif"
                                                    required>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <select name="country" class="form-control countries requiredField"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->country }} @endif" required>
                                                    <option value="">Select Country*</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" @if (!empty($shippingAddress) && $shippingAddress->country == $country->id) selected @endif>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <select name="state" class="form-control states requiredField" required>
                                                    <option value="@if (!empty($state)) {{ $state->id }} @endif" selected>
                                                        @if (!empty($state))
                                                        {{ $state->name }} @else Select State* @endif
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="city" class="form-control cities requiredField" required>
                                                    {{-- <option value="">Select City</option> --}}
                                                    <option value="@if (!empty($city)) {{ $city->id }} @endif" selected>
                                                        @if (!empty($city))
                                                        {{ $city->name }} @else Select City* @endif
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control requiredField" name="zip_code"
                                                    placeholder="Zip Code" value="@if (!empty($shippingAddress)) {{ $shippingAddress->zip_code }} @endif" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField" name="phone_number"
                                                    placeholder="Phone Number" name="phone_no"
                                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->phone_no }} @endif" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField" placeholder="Email"
                                                    name="email" value="@if (!empty($shippingAddress)) {{ $shippingAddress->email }} @endif" required>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="orangeBtn" id="addressBtn">Save and
                                                    Continue</button>
                                            </div>
                                        </form>
                                    @else
                                        <form action="" class="row formStyle billingForm" method="post">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="First Name*" name="first_name" value="" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="Last Name*" name="last_name" value="" required>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control"
                                                    placeholder="Company Name(Optional)" name="company_name" value="">
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="Street Line*" name="address1" value="" required>
                                            </div>
                                            {{-- <div class="col-md-12">
                                                <input type="text" class="form-control requiredField"
                                                    placeholder="Street Line*" name="address2" value="" required>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <select name="country" class="form-control countries requiredField"
                                                    required>
                                                    <option value="">Select Country*</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="state" class="form-control states requiredField" required>
                                                    <option value="">Select State*</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="city" class="form-control cities requiredField" required>
                                                    <option value="">Select City*</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control requiredField" name="zip_code"
                                                    placeholder="Zip Code*" value="" required>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField" name="phone_number"
                                                    placeholder="Phone Number*" name="phone_no" value="" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control requiredField" placeholder="Email*"
                                                    name="email" value="" required>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="orangeBtn" id="addressBtn">Save and
                                                    Continue</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"
                                    disabled>
                                    Shipping Selections
                                </button>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                data-parent="#accordionExample">
                                <div class="card-body shippingSelection">
                                    <form action="" method="POST" class="formStyle my-3 px-3" id="shippingForm">
                                        @forelse($products as $product)
                                            <div class="table-responsive mb-3" id="tab_{{ $product['productId'] }}">
                                                <div class="top"></div>
                                                <select name="shipping" id="shipping_selt"
                                                    data-id="{{ $product['productId'] }}"
                                                    class="form-control bg-white requiredField shipping_select" required>
                                                    <option value="">Select Shipping*</option>
                                                    @if ($product['product_type'] == 'Physical' && $product['shipping'] == 2)
                                                        <option value="0">No Shipping Applied</option>
                                                    @elseif ($product['product_type'] == 'Physical' &&
                                                        $product['shipping'] == 3)
                                                        <option value="{{ $product['shipping_charges'] }}">Shipping By
                                                            Seller ${{ $product['shipping_charges'] }}
                                                        </option>
                                                    @elseif ($product['product_type'] == 'Downloadable')
                                                        <option value="0">No Shipping Applied</option>

                                                    @endif
                                                </select>

                                                <table class="table table-border">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Product ({{ $product['quantity'] }})</th>
                                                            <th>Price</th>
                                                            <th>QTY</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                @if ($product['product_type'] == 'Physical' && $product['shipping'] == 1)
                                                                    <input type="checkbox" name="shippig"
                                                                        data-row_id="{{ $product['row_id'] }}"
                                                                        value="{{ $product['productId'] }}">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="cell">
                                                                    <div class="img">
                                                                        <img src="{{ productImage(@$product['product_image']) }}"
                                                                            alt="" class="img-fluid">
                                                                    </div>
                                                                    <div class="content">
                                                                        <h4>{{ $product['name'] }}</h4>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td> {{ presentPrice($product['price']) }}</td>
                                                            <td>{{ $product['quantity'] }}</td>
                                                            <td>{{ presentPrice($product['subtotal']) }}</td>
                                                        </tr>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5">
                                                                <span>Shipment Total:
                                                                    <strong class="shipping_cost"
                                                                        id="sep_ship_cost_{{ $product['productId'] }}">0</strong>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @empty
                                        @endforelse
                                        <button type="submit" class="orangeBtn">Save and Continue</button>
                                    </form>
                                </div>
                                <input type="hidden" id="shipping_costt_amnt">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="paymentHeading">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#paymentCollapse" aria-expanded="false" aria-controls="paymentCollapse"
                                    disabled>
                                    Additional Notes
                                </button>
                            </div>
                            <div id="paymentCollapse" class="collapse" aria-labelledby="paymentHeading"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <form action="" id="notesForm" method="POST">
                                        <textarea name="notes" cols="30" rows="10" class="form-control"
                                            placeholder="write your additional notes here..."></textarea>
                                        <button type="submit" class="orangeBtn">Save & Continue</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @php
                            $service = ($totalAmount * $settings->service_charges) / 100;
                            $tax = ($totalAmount * $settings->tax) / 100;
                            $total = $totalAmount + $tax;
                            
                            // $paypal_total = ($total / 100) * $settings->paypal_percentage + $settings->paypal_charges;
                            // $stripe_total = ($total / 100) * $settings->stripe_percentage + $settings->stripe_charges;
                            // $splitit_total = ($total / 100) * $settings->splitit_percentage + $settings->splitit_charges;
                            
                        @endphp
                        <div class="card">
                            <div class="card-header" id="paymentMethodHeading">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#paymentMethodHeadingCollapse" aria-expanded="false"
                                    aria-controls="paymentMethodHeadingCollapse" disabled>
                                    Payment Method
                                </button>
                            </div>
                            <div id="paymentMethodHeadingCollapse" class="collapse"
                                aria-labelledby="paymentMethodHeading" data-parent="#accordionExample">
                                <div class="card-body">
                                    <form action="" id="payment_method_form" method="POST">
                                        <select class="form-control requiredField" name="payment_method" id="payment_method"
                                            required title="Select Payment Method">
                                            <option value="">Select Payment</option>
                                        </select> <br>
                                        <button type="submit" class="orangeBtn">Save & Continue</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="confirmHeading">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#confirmCollapse" aria-expanded="false" aria-controls="confirmCollapse"
                                    disabled>
                                    Confirm
                                </button>
                            </div>
                            <div id="confirmCollapse" class="collapse" aria-labelledby="confirmHeading"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <label for="term_condition">
                                        <input type="checkbox" name="term_condition" id="term_condition"
                                            class="term_conditionn requiredField" required value="">
                                        I accept all <a href="{{ url('terms-conditions') }}"
                                            class="tenant">terms
                                            &amp;
                                            conditions
                                        </a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="orderSumry mt-lg-0">
                        <div class="head">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="summary-body">
                            <p>({{ Cart::count() }}) Items</p>
                            <strong>Subtotal
                                <span>
                                    ${{ $totalAmount }}
                                </span>
                            </strong>
                            <strong>Shipping <span class="shipping_cost" id="shipping_costt">$0</span></strong>
                            {{-- <strong>Tax <span id="tax"></span>0</strong> --}}
                            <strong style="display: none">Service Charges ({{ $settings->service_charges }}%)
                                <span
                                    id="service_charges">${{ $service = ($totalAmount * $settings->service_charges) / 100 }}</span>
                            </strong>
                            <strong>Tax
                                <span id="vat_charges">${{ sprintf('%.2f', $tax) }}</span>
                            </strong>
                        </div>
                        <div class="summary-footer">
                            <strong>Total <span
                                    id="totalamount">${{ sprintf('%.2f', $totalAmount + $tax) }}</span></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <h5>Note:</h5>
                    <p>Paypal Charges: ${{ $settings->paypal_charges }} + {{ $settings->paypal_percentage }}%
                        | Stripe Charges: ${{ $settings->stripe_charges }} + {{ $settings->stripe_percentage }}%
                        | SplitIT Charges: ${{ $settings->splitit_charges }} + {{ $settings->splitit_percentage }}%
                    </p>
                </div>

                <div class="col-md-4 mt-5" id="paypal_method_div" style="display: none">
                    <input id="nonce" name="payment_method_nonce" type="hidden" />
                    {{-- <div id="paypal-button-container"></div> --}}
                    <div id="paypal-button-container"></div>
                </div>
                <div class="col-md-4 mt-5" id="stripe_method_div" style="display: none">
                    <button class="button-primary paymentBtn" type="button" id="strip_btn" style="">
                        Pay with <i class="fab fa-cc-stripe"></i>
                    </button>
                </div>
                <div class="col-md-4 mt-5" id="splitit_method_div" style="display: none">
                    <button class="button-primary splitPaymentBtn" type="button" id="split_btn"
                        style="background-color:#401a43">
                        Pay with SPLIT IT
                    </button>
                </div>

                <input type="hidden" id="total_amount" name="total_amount"
                    value="{{ sprintf('%.2f', $totalAmount + $tax) }}">
            </div>
            @php
                $totalAmount = sprintf('%.2f', $totalAmount + $tax);
            @endphp
        </div>
    </section>

    <!-- Modal for payment -->
    <div id="myModal-payment" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form id="payment-form">
                    <div class="modal-header">
                        <h4 class="modal-title">Pay with credit card</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" value="" id="hidden-section">
                                            <input type="text" class="form-control" id="cardNumber" name="cardNumber"
                                                placeholder="Valid Card Number" required autofocus />
                                            <img src=""
                                                class="img-fluid cardLogo" />
                                            <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-lock"></span></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="expiryMonth"
                                                    name="expiryMonth" placeholder="MM" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="expiryYear" name="expiryYear"
                                                    placeholder="YYYY" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 pull-right">
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="cvCode" name="cvCode"
                                                    placeholder="CV" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class=''>
                                <div class='col-md-12'>
                                    <div class='form-control total btn btn-info'>
                                        <a href="javascript:void(0)" style="color: #fff;">
                                            <span class="badge pull-right">
                                                <input type="hidden" id="final-amount1" value="">
                                                <span id="final-amount" class="glyphicon glyphicon-usd"></span>
                                            </span> Final Payment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <span id="msg_span" class="text-center"></span>
                        </div>
                        <button class="btn btn-success btn-lg btn-block" type="submit" id="proceed">Pay</button>
                        {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Modal for payment SPLIT IT -->
    <div id="myModal-payment-split" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form id="split-payment-form">
                    <div class="modal-header">
                        <h4 class="modal-title">Pay with credit card</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" value="" id="hidden-section">
                                            <input type="text" class="form-control" id="card_holder_full_name"
                                                name="card_holder_full_name" placeholder="Card Holder Full Name" required
                                                autofocus />
                                            <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-lock"></span></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" value="" id="hidden-section">
                                            <input type="text" class="form-control" id="cardNumber"
                                                name="cardNumberSplit" placeholder="Valid Card Number" required autofocus />
                                            <img src="{{ URL::asset('front/images/card-logo.png') }}"
                                                class="img-fluid cardLogo" />
                                            <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-lock"></span></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="expiryMonth"
                                                    name="expiryMonthSplit" placeholder="MM" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="expiryYear"
                                                    name="expiryYearSplit" placeholder="YYYY" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="cvCode"
                                                    name="cvCodeSplit" placeholder="CVV" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">

                                        <div class="col-lg-6 col-md-6 pull-right">
                                            <div class="form-group">
                                                <label for="cvCode">
                                                    INSTALLMENTS</label>
                                                <select name="installmentSplit" id="installment" class="form-control">
                                                    <option value="4">4 Installment</option>
                                                    {{-- @for ($i = 1; $i < 10; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <span id="msg_span_split" class="text-center"></span>
                        </div>
                        <button class="btn btn-success btn-lg btn-block" type="submit" id="proceedSplit">Pay</button>
                        {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                    </div>
                </form>
            </div>

        </div>
    </div>


@endsection
@section('extra-js')
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD&disable-funding=credit,card"
        data-sdk-integration-source="button-factory"></script>

    {{-- <script src="https://www.paypal.com/sdk/js?client-id=AeZqLN-vpDaqlpt1LeIOmNT1A_I2q6-P60OIxyGlUXt32IPCXex8_g_t2J2lVUnCw4fSYt5_Wv0j0feM&components=messages,buttons"></script> --}}
    <script src="{{ asset('front/js/location.js') }}"></script>
    <script>
        $(document).ready(function() {

            // $('.loading').css('display', 'none');
            let product_type = "";
            $('.billingForm').submit(function(e) {
                e.preventDefault();

                $('.billingForm').parent().parent().removeClass('collapse show');
                $('.billingForm').parent().parent().addClass('collapse');
                // setTimeout(function() {
                // $('.loading').css('display', 'none');
                $('#collapseThree').removeClass('collapse');
                $('#collapseThree').addClass('collapsed collapse show');
                $('#headingThree button').removeAttr("disabled");
                // }, 2000)
            });

            $('input[name="shippig"]').on('change', function() {
                // console.log(this.value);

                if ($(this).prop('checked')) {
                    let pId = this.value;
                    let row_id = $(this).data('row_id');
                    $('.loading').css('display', 'block');
                    // if (product_type == "Physical") {
                    $('.loading').css('display', 'block');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: `{{ url('checkout/getFreghtRate') }}`,
                        type: 'get',
                        dataType: 'json',
                        data: {
                            'id': this.value,
                            'row_id': row_id,
                            'first_name': $('input[name="first_name"]').val(),
                            'last_name': $('input[name="last_name"]').val(),
                            // 'company_name': $('input[name="company_name"]').val(),
                            'address1': $('input[name="address1"]').val(),
                            'country': $('select[name="country"] option:selected').val(),
                            'state': $('select[name="state"] option:selected').val(),
                            'city': $('select[name="city"] option:selected').val(),
                            'zip_code': $('input[name="zip_code"]').val(),
                            'phone_number': $('input[name="phone_number"]').val(),
                            'email': $('input[name="email"]').val(),
                        },
                        success: function(data) {
                            if (data.status == true) {
                                delete data.status;
                                delete data.message;
                                // console.log(pId);
                                let selectBox = $('#tab_' + this.value).find(
                                    'select[name="shipping"]');
                                // $('select[name = "shipping"]').data('id')
                                $('#tab_' + pId).find('select[name="shipping"]').empty();
                                $('#tab_' + pId).find('select[name="shipping"]').append(
                                    $(
                                        '<option>', {
                                            value: '',
                                            text: 'Select Shipping'
                                        }));

                                $.each(data, function(index, item) {
                                    $('#tab_' + pId).find(
                                        'select[name="shipping"]').append(
                                        `<option value="${item.amount}" data-service="${index}" data-packaging="${item.packaging_type}">
                                                ${index} | ${item
                                                .currency_amount}
                                                </option>`
                                    );

                                });
                                $('.loading').css('display', 'none');

                            } else {
                                $('.loading').css('display', 'none');
                                toastr.error(data.message, 'Error!');
                            }
                        },
                        error: function(xhr) {
                            // alert();
                            console.log(xhr);
                            $('.loading').css('display', 'none');
                            alert(xhr.responseJSON.message);
                        }
                    });

                    // setTimeout(function() {
                    //     // $('.loading').css('display', 'none');
                    //     $('#collapseThree').removeClass('collapse');
                    //     $('#collapseThree').addClass('collapsed collapse show');
                    //     $('#headingThree button').removeAttr("disabled");
                    // }, 2000)
                    // } else {
                    //     $('#shipping_selt').removeClass("requiredField");
                    //     setTimeout(function() {
                    //         $('.loading').css('display', 'none');
                    //         $('#paymentCollapse').removeClass('collapse');
                    //         $('#paymentCollapse').addClass('collapsed collapse show');
                    //         $('#paymentHeading button').removeAttr("disabled");
                    //     }, 2000)
                    // }



                    // });
                }
            });


            $('#shippingForm').submit(function(e) {
                e.preventDefault();

                $('#shippingForm').parent().parent().removeClass('collapse show');
                $('#shippingForm').parent().parent().addClass('collapse');
                $('#paymentCollapse').removeClass('collapse');
                $('#paymentCollapse').addClass('collapsed collapse show');
                $('#paymentHeading button').removeAttr("disabled");
            });

            $('#notesForm').submit(function(e) {
                e.preventDefault();

                $('#notesForm').parent().parent().removeClass('collapse show');
                $('#notesForm').parent().parent().addClass('collapse');
                $('#confirmCollapse').removeClass('collapse');

                // $('#confirmCollapse').addClass('collapsed collapse show');
                // $('#confirmHeading button').removeAttr("disabled");

                $('#paymentMethodHeadingCollapse').removeClass('collapse');
                $('#paymentMethodHeadingCollapse').addClass('collapsed collapse show');
                $('#paymentMethodHeading button').removeAttr("disabled");
            });

            $('#payment_method_form').submit(function(e) {
                e.preventDefault();

                $('#confirmCollapse').addClass('collapsed collapse show');
                $('#confirmHeading button').removeAttr("disabled");

                $('#paymentMethodHeadingCollapse').removeClass('collapse show');
                $('#paymentMethodHeadingCollapse').addClass('collapse');


                // $('#confirmCollapse').removeClass('collapse');
                // $('#paymentMethodHeadingCollapse').removeClass('collapse');
                // $('#paymentMethodHeadingCollapse').addClass('collapsed collapse show');
                // $('#paymentMethodHeading button').removeAttr("disabled");
            });

            $('select[name="payment_method"]').on('change', function() {
                let payment_method = $(this).val();
                let grand_total = "{{ $totalAmount }}";

                let amount = 0;
                let final_total = 0;
                let shipping_cost = $('#shipping_costt_amnt').val();

                if (payment_method == "paypal") {
                    $('#paypal_method_div').css('display', 'block');

                    amount = $(this).find(':selected').data('val');
                    final_total = parseFloat(grand_total) + parseFloat(amount) + parseFloat(shipping_cost);

                    $('#totalamount').text('$' + final_total.toFixed(2));
                    $('#total_amount').val(final_total.toFixed(2));

                    $('#stripe_method_div').css('display', 'none');
                    $('#splitit_method_div').css('display', 'none');

                } else if (payment_method == "stripe") {

                    $('#stripe_method_div').css('display', 'block');
                    amount = $(this).find(':selected').data('val');
                    final_total = parseFloat(grand_total) + parseFloat(amount) + parseFloat(shipping_cost);

                    $('#totalamount').text('$' + final_total.toFixed(2));
                    $('#total_amount').val(final_total.toFixed(2));
                    $('#paypal_method_div').css('display', 'none');
                    $('#splitit_method_div').css('display', 'none');
                } else if (payment_method == "splitit") {

                    $('#splitit_method_div').css('display', 'block');
                    amount = $(this).find(':selected').data('val');
                    final_total = parseFloat(grand_total) + parseFloat(amount) + parseFloat(shipping_cost);

                    $('#totalamount').text('$' + final_total.toFixed(2));
                    $('#total_amount').val(final_total.toFixed(2));
                    $('#paypal_method_div').css('display', 'none');
                    $('#stripe_method_div').css('display', 'none');
                } else {
                    $('#paypal_method_div').css('display', 'none');
                    let tt = parseFloat(grand_total) + parseFloat(shipping_cost);

                    $('#totalamount').text('$' + tt.toFixed(2));
                    $('#total_amount').val(tt.toFixed(2));

                    $('#stripe_method_div').css('display', 'none');
                    $('#splitit_method_div').css('display', 'none');
                }
            });

            $('select[name="shipping"]').on('change', function() {
                let shipping_cost_total = 0;
                let grand_total = 0;
                let paypal_total = 0;
                let paypal_chargee = '{{ $settings->paypal_charges }}';
                let paypal_percent = '{{ $settings->paypal_percentage }}';

                let stripe_total = 0;
                let stripe_chargee = '{{ $settings->stripe_charges }}';
                let stripe_percent = '{{ $settings->stripe_percentage }}';

                let splitit = 0;
                let splitit_chargee = '{{ $settings->splitit_charges }}';
                let splitit_percent = '{{ $settings->splitit_percentage }}';


                $('.shipping_select').each(function(i, obj) {
                    if (obj.value > 0) {
                        shipping_cost_total += parseFloat(obj.value);
                    }
                });

                if (shipping_cost_total > 0) {

                    $(this).data('id');
                    $('#sep_ship_cost_' + $(this).data('id')).text('$' + this.value);
                    let total_amount = '{{ $totalAmount }}';

                    total_amount = parseFloat(total_amount) + parseFloat(shipping_cost_total);

                    paypal_total = ((total_amount / 100) *
                        paypal_percent) + parseFloat(paypal_chargee);

                    stripe_total = ((total_amount / 100) *
                        stripe_percent) + parseFloat(stripe_chargee);

                    splitit_total = ((total_amount / 100) *
                        splitit_percent) + parseFloat(splitit_chargee);
                    $('#payment_method').empty();

                    $('#payment_method').append(
                        '<option data-val="" value="">Select Payment Method</option>');

                    $('#payment_method').append('<option data-val="' + paypal_total.toFixed(2) +
                        '" value="paypal">Paypal</option>');

                    $('#payment_method').append('<option data-val="' + stripe_total.toFixed(2) +
                        '" value="stripe">Stripe</option>');
                    $('#payment_method').append(
                        '<option data-val="' + splitit_total.toFixed(2) +
                        '" value="splitit">Split IT</option>');

                    // $('#payment_method').append($('<option data-val="' + paypal_total + '">', {
                    //     value: 'paypal',
                    //     text: 'Paypal',
                    // }));

                    // $('#payment_method').append($('<option data-val="' + stripe_total + '">', {
                    //     value: 'stripe',
                    //     text: 'Stripe',
                    // }));

                    // $('#payment_method').append($('<option data-val="' + splitit_total + '">', {
                    //     value: 'splitit',
                    //     text: 'splitit',
                    // }));


                    let total_amount_sent = "{{ $totalAmount }}";
                    let float_num_sent = parseFloat(total_amount_sent) + parseFloat(
                        shipping_cost_total);
                    let float_num = parseFloat(total_amount_sent) + parseFloat(
                        shipping_cost_total);
                    float_num.toFixed(2);
                    float_num_sent
                        .toFixed(2);
                    $('#totalamount').text('$' + float_num.toFixed(2));
                    $('#total_amount').val(float_num_sent.toFixed(2));
                    $('#shipping_costt').text('$' + shipping_cost_total.toFixed(2));
                    $('#shipping_costt_amnt').val(shipping_cost_total.toFixed(2));


                    // console.log(float_num_sent.toFixed(2));
                } else {
                    $('#sep_ship_cost_' + $(this).data('id')).text('$' + 0);
                    let total_amount = '{{ $totalAmount }}';
                    // total_amount.replace("$", "");
                    // console.log(total_amount);
                    let total_amount_sent = "{{ $totalAmount }}";
                    let float_num_sent = parseFloat(total_amount_sent) + parseFloat(0);
                    let float_num = parseFloat(total_amount_sent) + parseFloat(0);
                    float_num.toFixed(2);
                    float_num_sent.toFixed(2);

                    paypal_total = ((total_amount / 100) *
                        paypal_percent) + parseFloat(paypal_chargee);

                    stripe_total = ((total_amount / 100) *
                        stripe_percent) + parseFloat(stripe_chargee);

                    splitit_total = ((total_amount / 100) *
                        splitit_percent) + parseFloat(splitit_chargee);
                    $('#payment_method').empty();

                    $('#payment_method').append(
                        '<option data-val="" value="">Select Payment Method</option>');

                    $('#payment_method').append('<option data-val="' + paypal_total.toFixed(2) +
                        '" value="paypal">Paypal</option>');

                    $('#payment_method').append('<option data-val="' + stripe_total.toFixed(2) +
                        '" value="stripe">Stripe</option>');
                    $('#payment_method').append(
                        '<option data-val="' + splitit_total.toFixed(2) +
                        '" value="splitit">SplitIT</option>');


                    $('#totalamount').text('$' + float_num.toFixed(2));
                    $('#total_amount').val(float_num_sent.toFixed(2));
                    $('#shipping_costt').text('$' + 0);
                    $('#shipping_costt_amnt').val(shipping_cost_total.toFixed(2));
                }

            });
        });

        $(document).ready(function() {
            initPayPalButton();

            $('input[name="term_condition"]').change(function() {
                if ($('#term_condition').is(":checked")) {
                    $('input[name="term_condition"]').val("yes");
                } else {
                    $('input[name="term_condition"]').val("");
                }
            });


        });

        //Paypal Payment

        function initPayPalButton() {
            paypal.Buttons({
                style: {
                    shape: 'rect',
                    color: 'gold',
                    layout: 'vertical',
                    label: 'paypal',
                },
                onClick: function(data, actions) {
                    //actions.reject();
                    let y = document.getElementsByClassName("requiredField");
                    valid = true;
                    for (i = 0; i < y.length; i++) {

                        if (y[i].value == "") {
                            y[i].className += " is-invalid";
                            valid = false;
                        }
                    }
                    if (valid) {
                        return actions.resolve();
                    } else {
                        toastr.error('Please fill all Required Fields', 'Error!');
                        $("html, body").animate({
                            scrollTop: 0
                        }, "slow");
                        return actions.reject();
                    }
                    // console.log('click');
                },
                createOrder: function(data, actions) {
                    let amount = $('#total_amount').val();
                    return actions.order.create({
                        purchase_units: [{
                            "amount": {
                                "currency_code": "USD",
                                "value": amount
                            }
                        }]
                    });
                },

                onApprove: function(data, actions) {
                    let amount = $('#total_amount').val();
                    return actions.order.capture().then(function(details) {

                        let first_name = $('input[name="first_name"]').val();
                        let last_name = $('input[name="last_name"]').val();
                        let email = $('input[name="email"]').val();
                        let phone = $('input[name="phone_number"]').val();

                        //billing
                        let address1 = $('input[name="address1"]').val();
                        // let address2 = $('input[name="address2"]').val();
                        let country = $('select[name="country"]').val();
                        let city = $('select[name="city"]').val();
                        let state = $('select[name="state"]').val();
                        let zip_code = $('input[name="zip_code"]').val();

                        let shipping_cost = $('#shipping_costt_amnt').val();
                        // shipping_cost.replace("$", "");
                        let shipping = [];
                        let packaging_type = [];
                        let shipping_cost_array = [];
                        // $("#shipping_selt option:selected").data('service');
                        $('.shipping_select option:selected').each(function(i,
                            obj) {
                            shipping.push($(this).attr('data-service'));
                            packaging_type.push($(this).attr(
                                'data-packaging'));
                            shipping_cost_array.push($(this).val());
                        });
                        // let packaging_type = $("#shipping_selt option:selected").data('packaging');
                        let term_condition;

                        if ($('#term_condition:checked').is(":checked")) {
                            term_condition = "yes";
                        } else {
                            term_condition = null
                        }
                        let service_charges = $('#service_charges').text();
                        service_charges = service_charges.replace("$", "");
                        let vat_charges = $('#vat_charges').text();
                        vat_charges = vat_charges.replace("$", "");
                        let note = $('#note').val();
                        let p_id = '';

                        $('.loading').css('display', 'block');
                        $.ajax({
                            url: "{{ url('paypalCharge') }}",
                            data: {
                                amount: amount,
                                first_name: first_name,
                                last_name: last_name,
                                email: email,
                                phone: phone,
                                address1: address1,
                                // address2: address2,
                                country: country,
                                city: city,
                                zip_code: zip_code,
                                state: state,
                                shipping: shipping,
                                shipping_cost: shipping_cost,
                                packaging_type: packaging_type,
                                shipping_cost_array: shipping_cost_array,
                                term_condition: term_condition,
                                service_charges: service_charges,
                                vat_charges: vat_charges,
                                note: note,
                                p_id: p_id
                            },
                            method: 'POST',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $(
                                    'meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(res) {
                                // console.log(res);

                                if (!res.status) {
                                    toastr.error(`${res.errors}`);
                                    $("html, body").animate({
                                        scrollTop: 0
                                    }, "slow");
                                    $('.loading').css('display',
                                        'none');
                                } else {
                                    window.location =
                                        `/checkout/${res.data.order_no}/success`;
                                    $('.loading').css('display',
                                        'none');
                                }
                                // window.location = "{{ url('checkout/success') }}"
                            },
                            error: function(request, status, error) {
                                $('.loading').css('display',
                                    'none');
                                // alert(request.responseText);
                                toastr.error(
                                    `${request.responseText}`);
                            }
                        })
                    });
                },
                onError: function(err) {
                    $('.loading').css('display', 'none');
                    console.log(err);
                }
            }).render('#paypal-button-container');



            //Stripe Payment
            $("#payment-form").submit(function(e) {
                e.preventDefault();
                $('#msg_span').text("Processing..");
                $('.loading').css('display', 'block');
                $('#proceed').prop('disabled', true);
                let amount = $('#total_amount').val();
                let card_no = $('#cardNumber').val();
                let exp_mon = $('#expiryMonth').val();
                let exp_year = $('#expiryYear').val();
                let cvv = $('#cvCode').val();

                let first_name = $('input[name="first_name"]').val();
                let last_name = $('input[name="last_name"]').val();
                let email = $('input[name="email"]').val();
                let phone = $('input[name="phone_number"]').val();

                //billing
                let address1 = $('input[name="address1"]').val();
                // let address2 = $('input[name="address2"]').val();
                let country = $('select[name="country"]').val();
                let city = $('select[name="city"]').val();
                let state = $('select[name="state"]').val();
                let zip_code = $('input[name="zip_code"]').val();

                let shipping_cost = $('#shipping_costt_amnt').val();
                // shipping_cost.replace("$", "");
                // console.log(shipping_cost);
                let shipping = [];
                let packaging_type = [];
                let shipping_cost_array = [];
                // $("#shipping_selt option:selected").data('service');
                $('.shipping_select option:selected').each(function(i, obj) {
                    shipping.push($(this).attr('data-service'));
                    packaging_type.push($(this).attr('data-packaging'));
                    shipping_cost_array.push($(this).val());
                });

                //  = $("#shipping_selt option:selected").data('packaging');
                let term_condition;

                if ($('#term_condition:checked').is(":checked")) {
                    term_condition = "yes";
                } else {
                    term_condition = null
                }

                let service_charges = $('#service_charges').text();
                service_charges = service_charges.replace("$", "");
                let vat_charges = $('#vat_charges').text();
                vat_charges = vat_charges.replace("$", "");
                let note = $('#note').val();
                let p_id = '';

                let ship_address_check = $('#ship_address').val();
                let order_no = null;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `{{ url('stripeCharge') }}`,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        amount: amount,
                        card_no: card_no,
                        exp_mon: exp_mon,
                        exp_year: exp_year,
                        cvv: cvv,
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        phone: phone,
                        address1: address1,
                        // address2: address2,
                        country: country,
                        city: city,
                        zip_code: zip_code,
                        state: state,
                        shipping: shipping,
                        shipping_cost: shipping_cost,
                        packaging_type: packaging_type,
                        shipping_cost_array: shipping_cost_array,
                        term_condition: term_condition,
                        service_charges: service_charges,
                        vat_charges: vat_charges,
                        note: note,
                        p_id: p_id
                    },
                    success: function(data) {
                        //console.log(data);
                        $('.errorField').css('display', 'none');

                        if (!data.status) {
                            $('#myModal-payment').modal('hide');
                            $('#msg_span').css('display', 'none');
                            $('#proceed').prop("disabled", false);
                            $('.loading').css('display', 'none');
                            toastr.error(`${data.errors}`);
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                        } else {
                            order_no = data.data.order_no;
                            $('#msg_span').text("");
                            $('#myModal-payment').modal('hide');
                            window.location = `/checkout/${order_no}/success`;
                            $('.loading').css('display', 'none');
                        }

                        // postJob();

                    },
                    error: function(xhr) {

                        $('.loading').css('display', 'none');
                        $('#msg_span').text(xhr.responseJSON.message);
                        $('#proceed').prop('disabled', false);
                    }
                });
            });

            //Strip btn Validation
            $('#strip_btn').on('click', function() {
                let y = document.getElementsByClassName("requiredField");

                valid = true;
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].value == "") {
                        // add an "invalid" class to the field:
                        y[i].className += " is-invalid";
                        // console.log('not valid'+y[i]);
                        // and set the current valid status to false
                        valid = false;
                    }

                }
                if (!valid) {
                    toastr.error('Please fill all Required Fields', 'Error!');
                    $("html, body").animate({
                        scrollTop: 0
                    }, "slow");
                }
                if (valid) {

                    $('#proceed').text('Pay ' + $('#totalamount').text());
                    $('#myModal-payment').modal('show');
                }
            });

            //Splitit btn Validation
            $('#split_btn').on('click', function() {
                let y = document.getElementsByClassName("requiredField");
                valid = true;
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].value == "") {
                        // add an "invalid" class to the field:
                        y[i].className += " is-invalid";
                        // console.log('not valid'+y[i]);
                        // and set the current valid status to false
                        valid = false;
                    }

                }
                if (!valid) {
                    toastr.error('Please fill all Required Fields', 'Error!');
                    $("html, body").animate({
                        scrollTop: 0
                    }, "slow");
                }
                if (valid) {
                    // $('#final-amount').text($('#totalamount').text());
                    // console.log($('#totalamount').text());
                    $('#proceedSplit').text('Pay ' + $('#totalamount').text());
                    $('#myModal-payment-split').modal('show');
                }
            });

            //Stripe Payment
            $("#split-payment-form").submit(function(e) {
                e.preventDefault();
                $('#msg_span_split').text("Processing..");
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                $('.loading').css('display', 'block');
                $('#proceedSplit').prop('disabled', true);
                let amount = $('#total_amount').val();
                let card_no = $('input[name="cardNumberSplit"]').val();
                let card_holder_full_name = $('input[name="card_holder_full_name"]').val();
                let exp_mon = $('input[name="expiryMonthSplit"]').val();
                let exp_year = $('input[name="expiryYearSplit"]').val();
                let cvv = $('input[name="cvCodeSplit"]').val();
                let installments = $('select[name="installmentSplit"]').val();

                let first_name = $('input[name="first_name"]').val();
                let last_name = $('input[name="last_name"]').val();
                let email = $('input[name="email"]').val();
                let phone = $('input[name="phone_number"]').val();

                //billing
                let address1 = $('input[name="address1"]').val();
                // let address2 = $('input[name="address2"]').val();
                let country = $('select[name="country"]').val();
                let city = $('select[name="city"]').val();
                let state = $('select[name="state"]').val();
                let zip_code = $('input[name="zip_code"]').val();

                let shipping_cost = $('#shipping_costt_amnt').val();
                // shipping_cost.replace("$", "");
                let shipping = [];
                let packaging_type = [];
                let shipping_cost_array = [];
                // $("#shipping_selt option:selected").data('service');
                $('.shipping_select option:selected').each(function(i, obj) {
                    shipping.push($(this).attr('data-service'));
                    packaging_type.push($(this).attr('data-packaging'));
                    shipping_cost_array.push($(this).val());
                });
                // let shipping = $("#shipping_selt option:selected").data('service');
                // let packaging_type = $("#shipping_selt option:selected").data('packaging');
                let term_condition;

                if ($('#term_condition:checked').is(":checked")) {
                    term_condition = "yes";
                } else {
                    term_condition = null
                }
                let service_charges = $('#service_charges').text();
                service_charges = service_charges.replace("$", "");
                let vat_charges = $('#vat_charges').text();
                vat_charges = vat_charges.replace("$", "");
                let note = $('#note').val();
                let p_id = '';
                // let ship_address_check = $('#ship_address').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `{{ url('splitItCharge') }}`,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        amount: amount,
                        card_no: card_no,
                        card_holder_full_name: card_holder_full_name,
                        exp_mon: exp_mon,
                        exp_year: exp_year,
                        cvv: cvv,
                        installments: installments,
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        phone: phone,
                        address1: address1,
                        // address2: address2,
                        country: country,
                        city: city,
                        zip_code: zip_code,
                        state: state,
                        shipping: shipping,
                        shipping_cost: shipping_cost,
                        packaging_type: packaging_type,
                        shipping_cost_array: shipping_cost_array,
                        term_condition: term_condition,
                        service_charges: service_charges,
                        vat_charges: vat_charges,
                        note: note,
                        p_id: p_id
                    },
                    success: function(data) {
                        // console.log(data);
                        $('.loading').css('display', 'none');
                        if (!data.status) {
                            $('#myModal-payment').modal('hide');
                            $('#msg_span_split').css('display', 'none');
                            $('#proceedSplit').prop("disabled", false);
                            $('.errorField').css('display', 'none');
                            toastr.error(`${data.errors}`);
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                        } else {
                            $('#msg_span_split').text("");

                            $('#myModal-payment-split').modal('hide');
                            window.location =
                                `/checkout/${data.data.order_no}/success`;
                            $('.loading').css('display', 'none');
                        }

                    },
                    error: function(xhr) {
                        // console.log(xhr);
                        $('.loading').css('display', 'none');
                        $('#msg_span_split').css('display', 'block');
                        $('#msg_span_split').text(xhr.responseJSON.message);
                        $('#proceedSplit').prop('disabled', false);
                    }
                });
            });

        }
    </script>
@endsection
