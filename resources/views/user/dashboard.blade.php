@extends('front.layout.app')
@section('title','Dashboard')
@section('content')
    <!-- Begin: Account Section -->
    <section class="myAccount">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="nav nav-tabs tabNavStyle" id="bestSellerTab" role="tablist">
                        <li>
                            <div class="userNamePic">
                                <img src="{{userProfilePicture(@Auth::user()->profile_picture)}}" alt="">
                                <div class="content">
                                    <h4>Hello</h4>
                                    <p>{{Auth::user()->customers->first_name}} {{Auth::user()->customers->last_name}}</p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab"
                               aria-controls="dashboard" aria-selected="false"><i class="fal fa-desktop-alt"></i> My Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab"
                               aria-controls="account" aria-selected="false"><i class="fal fa-user"></i> My Account Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab"
                               aria-controls="orders" aria-selected="true"><i class="fal fa-box"></i> My Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="wishlist-tab" data-toggle="tab" href="#wishlist" role="tab"
                               aria-controls="wishlist" aria-selected="false"><i class="fal fa-heart"></i> My Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab"
                               aria-controls="address" aria-selected="false"><i class="fal fa-book"></i> My Addresses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-8">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="dashboard">
                            <div class="myDashboardTab">
                                <h2 class="title">My Dashboard</h2>
                                <div class="mt-3 mb-5">
                                    <h4>My Dashboard</h4>
                                    <p>From this dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                                </div>
                                <h4>Recent Orders</h4>
                                    @if(count($recentOrders) > 0)
                                        <div class="noRecord">
                                            <table class="table orderTable">
                                                <thead>
                                                <tr>
                                                    <th>Order#</th>
                                                    <th>Date</th>
                                                    <th>Order Total</th>
                                                    <th>Order Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($recentOrders as $order)
                                                    <tr>
                                                        <td>
                                                            <p><span>Order#</span>{{$order->order_no}}</p>
                                                        </td>
                                                        <td>
                                                            <p><span>Date</span>{{date('d-m-Y',strtotime($order->created_at))}}</p>
                                                        </td>
                                                        <td>
                                                            <p><span>Order Total</span>${{$order->total_amount+$order->shipping_cost}}</p>
                                                        </td>
                                                        <td>
                                                            <p style="text-transform: uppercase;"><span>Order Status</span>{{$order->order_status}}</p>
                                                        </td>
                                                        <td>
                                                            {{--                                                <a href="#" class="btnStyle btn-block">Track Order</a>--}}
                                                            <a href="javascript:void(0)" class="btnStyle orderDetailBtn"  data-Order_id="{{$order->id}}">View Order</a>
                                                            {{--                                                <a href="#" class="btnStyle">Reorder</a>--}}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="noRecord">
                                            <p>No Record Found</p>
                                        </div>
                                    @endif
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="account">
                            <div class="accounTab">
                                <h2 class="title">My Account Information</h2>
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{url('user/updateAccountInformation')}}" class="row formStyle" method="post">
                                    @csrf
                                    <div class="col-md-6">
                                        <label>First Name <span>*</span></label>
                                        <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{Auth::user()->customers->first_name}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Last Name <span>*</span></label>
                                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{Auth::user()->customers->last_name}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email <span>*</span></label>
                                        <input type="text" class="form-control" placeholder="Email" disabled value="{{Auth::user()->email}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mobile Number <span>*</span></label>
                                        <div class="CNum">
                                            <div class="input-group">
                                                <input type="tel" class="form-control phone_no" name="phone_no" id="phone_no" placeholder="50 123 4567" value="{{Auth::user()->customers->phone_no}}" required>
                                                <input type="hidden" class="form-control" name="country_code" id="country_code" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Gender</label>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="male" name="gender" class="custom-control-input" value="male" @if(Auth::user()->customers->gender == 'male') checked @endif>
                                            <label class="custom-control-label" for="male">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="female" name="gender" class="custom-control-input" value="female" @if(Auth::user()->customers->gender == 'female') checked @endif>
                                            <label class="custom-control-label" for="female">Female</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subscribeCheckBox" name="notification_check" value="1" @if(Auth::user()->customers->notification_check == 1) checked @endif>
                                            <label class="custom-control-label" for="subscribeCheckBox">I would like to receive emails and SMS regarding the promotions and special offers.</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="changePasswordCheckbox" name="change_pass_check" value="yes">
                                            <label class="custom-control-label" for="changePasswordCheckbox">Change Password?</label>
                                        </div>
                                        <div class="text-right"><button class="btnStyle">Save</button></div>
                                    </div>
                                    <div class="changePassword">
                                        <div class="col-md-12">
                                            <label>Old Password <span>*</span></label>
                                            <input type="password" class="form-control" placeholder="" name="old_password" >
                                        </div>
                                        <div class="col-md-12">
                                            <label>New Password (At least 6 characters) <span>*</span></label>
                                            <input type="password" class="form-control" placeholder="" name="new_password">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Confirm New Password <span>*</span></label>
                                            <input type="password" class="form-control" placeholder="" name="confirm_password">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="orders">
                            <div class="orderTab">
                                <h2 class="title">My Orders</h2>
                                <table class="table orderTable">
                                    <thead>
                                    <tr>
                                        <th>Order#</th>
                                        <th>Date</th>
                                        <th>Order Total</th>
                                        <th>Order Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>
                                                <p><span>Order#</span>{{$order->order_no}}</p>
                                            </td>
                                            <td>
                                                <p><span>Date</span>{{date('d-m-Y',strtotime($order->created_at))}}</p>
                                            </td>
                                            <td>
                                                <p><span>Order Total</span>${{$order->total_amount+$order->shipping_cost}}</p>
                                            </td>
                                            <td>
                                                <p style="text-transform: uppercase;"><span>Order Status</span>{{$order->order_status}}</p>
                                            </td>
                                            <td>
{{--                                                <a href="#" class="btnStyle btn-block">Track Order</a>--}}
                                                <a href="javascript:void(0)" class="btnStyle orderDetailBtn"  data-Order_id="{{$order->id}}">View Order</a>
{{--                                                <a href="#" class="btnStyle">Reorder</a>--}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Order yet!</td>
                                        </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wishlist">
                            <h2 class="title">My Wishlist</h2>
                            <table class="table orderTable wishlistTable">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <img src="{{ productImage($product->product->product_image) }}" alt="" class="img-fluid">
                                            <p>{{$product->product->product_name}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p>${{$product->product->product_current_price}}</p>
                                    </td>
                                    <td>
                                        <form action="{{ url('cart/store', $product->product->id) }}" method="POST" id="cart-form">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btnStyle">Add to cart</button>
                                            <a href="javascript:void(0)" class="btnStyle remove_to_wishlist" data-product="{{ $product->product->id }}" data-wishlist='{{ $product->id }}' data-customer="{{ Auth::user()->customer->id ?? 0 }}">Remove</a>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr >
                                        <td class="text-center" colspan="3">No Item in WishList !</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="address">
                            <h2 class="title">My Address <a href="javascript:void(0)" class="btnStyle" data-toggle="modal" data-target="#addNewsAddressModal"><i class="fa fa-plus-circle"></i> Add New Address</a></h2>
                            <div class="row">
                                @forelse($addresses as $addresse)
                                <div class="col-lg-6">
                                    <div class="adressCard">
                                        <span class="tag">{{$addresse->shipping_billing}}</span>
                                        <address>
                                            <span>{{$addresse->first_name}} {{$addresse->last_name}}</span>
                                            <span>{{$addresse->address}}</span>
                                            <span>{{$addresse->city}}</span>
                                            <span>{{$addresse->country}}</span>
                                            <span>{{$addresse->phone_no}}</span>
                                        </address>
                                        <a href="javascript:void(0)"  data-id="{{$addresse->id}}" class="edit editAddress"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" class="remove removeAddress"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                @empty
                                    <div class="col-lg-12">
                                        <div class="adressCard">
                                            <h5>No Address Saved Yet!</h5>
                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Account Section -->

    <!-- Add New Address Modal-->
    <div class="modal fade addNewsAddressModal" id="addAddress" tabindex="-1" aria-labelledby="addNewsAddressModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title m-0">Shipping Address</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{url('user/addCustomerAddress')}}" method="post" class="row formStyle" id="addAddressForm">
                        @csrf
                        <div class="col-md-6">
                            <label>First Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label>Last Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label>Mobile Number <span>*</span></label>
                            <div class="CNum">
                                <div class="input-group">
                                    <input type="tel" class="form-control phone_no" placeholder="50 123 4567" name="add_phone_no" required>
                                    <input type="hidden" class="form-control"  name="phone_no_code" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Title <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label>Street Address <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="address" required>
                        </div>
                        <div class="col-md-6">
                            <label>Shipping/Billing <span>*</span></label>
                            <select class="form-control" name="shipping_billing" id="shipping_billing" required>
                                <option value="">Select Type</option>
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Company Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="company_name" required>
                        </div>

                        <div class="col-md-6">
                            <label>Country <span>*</span></label>
                            <select class="form-control countries" name="country" id="countryId" required>

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>State <span>*</span></label>
                            <select class="form-control states" name="state" id="stateId" required>
                                <option value=""> - Select City -</option>

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>City <span>*</span></label>
                            <select class="form-control cities" name="city" id="cityId" required>
                                <option value=""> - Select City -</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Zip Code <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="zip_code" required>
                        </div>

                        <div class="col-md-12">
{{--                            <div class="custom-control custom-checkbox ml-3">--}}
{{--                                <input type="checkbox" class="custom-control-input" id="subscribeCheckBox" name="save_check" value="1">--}}
{{--                                <label class="custom-control-label" for="subscribeCheckBox">Save in address book</label>--}}
{{--                            </div>--}}
                            <div class="text-right border-top py-4 mt-4">
                                <button class="btn-border">Cancel</button>
                                <button type="submit" class="btnStyle">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Address Modal-->
    <div class="modal fade accountAccesSec" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title m-0">Edit Address</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{url('user/updateCustomerAddress')}}" method="post" class="row formStyle" id="updateAddress">
                        @csrf
                        <div class="col-md-6">
                            <label>First Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="first_name" id="first_name_e" required>
                        </div>
                        <div class="col-md-6">
                            <label>Last Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="last_name" id="last_name_e" required>
                        </div>
                        <div class="col-md-6">
                            <label>Mobile Number <span>*</span></label>
                            <div class="CNum">
                                <div class="input-group">
                                    <input type="tel" class="form-control phone_no" placeholder="50 123 4567" name="phone_no" id="phone_no_e" required>
                                    <input type="hidden" class="form-control"  name="phone_no_code_e" id="phone_no_code_e" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Title <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="title" id="title_e" required>
                        </div>
                        <div class="col-md-6">
                            <label>Street Address <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="address" id="address_e" required>
                        </div>
                        <div class="col-md-6">
                            <label>Shipping/Billing <span>*</span></label>
                            <select class="form-control" name="shipping_billing_e" id="shipping_billing_e" required>
                                <option value="">Select Type</option>
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Company Name <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="company_name" id="company_name_e" required>
                        </div>

                        <div class="col-md-6">
                            <label>Country <span>*</span></label>
                            <select class="form-control" name="country" id="country_e" required>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>State <span>*</span></label>
{{--                            <input type="text" class="form-control" placeholder="" name="state" id="state_e" required>--}}
                            <select class="form-control" name="state" id="state_e" required>
{{--                                @foreach($states as $state)--}}
{{--                                    <option value="{{$state->id}}">{{$state->name}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>City <span>*</span></label>
                            <select class="form-control" name="city" id="city_e" required>
{{--                                @foreach($cities as $city)--}}
{{--                                    <option value="{{$city->id}}">{{$city->name}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Zip Code <span>*</span></label>
                            <input type="text" class="form-control" placeholder="" name="zip_code" id="zip_code_e" required>
                        </div>
                        <input type="hidden" name="id" id="add_id">
                        <div class="col-md-12">
                            {{--                            <div class="custom-control custom-checkbox ml-3">--}}
                            {{--                                <input type="checkbox" class="custom-control-input" id="subscribeCheckBox" name="save_check" value="1">--}}
                            {{--                                <label class="custom-control-label" for="subscribeCheckBox">Save in address book</label>--}}
                            {{--                            </div>--}}
                            <div class="text-right border-top py-4 mt-4">
                                <button class="btn-border">Cancel</button>
                                <button type="submit" class="btnStyle">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal-->
    <div class="modal fade accountAccesSec" id="OrderDetailModal" tabindex="-1" aria-labelledby="OrderDetailModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="m-0">My Order Details</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="">
                    <div class="orderTab" id="OrderDetailModalBody">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')

    <script src="{{asset('front/js/location.js')}}"></script>
    <script>
    
        $('document').ready(function () {
            $(".phone_no").intlTelInput({
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
            });
            // $('.country_code').on('click',function () {
            //     $('#country_code').val($(this).data('code'));
            // });
            //
            // $('.address_country_code').on('click',function () {
            //     $('input[name="phone_no_code"]').val($(this).data('code'));
            // });
            // $('.address_country_code_e').on('click',function () {
            //     $('input[name="phone_no_code_e"]').val($(this).data('code'));
            // });
            $("#phone_no").intlTelInput("setNumber", '{{Auth::user()->customers->country_code.Auth::user()->customers->phone_no}}');


            $('.orderDetailBtn').on('click',function () {
                $order_id = $(this).data('order_id');

                $.ajax({
                    url: "{{url('user/getOrderDetail')}}/"+$order_id,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res){
                        // console.log(res);
                        var html = '';
                        let total_amount = parseInt(res.total_amount)+parseInt(res.shipping_cost);
                        html += `<div class="alert alert-success" role="alert">
                            <span>Order# ${res.order_no}</span>
                            <span>Total: $${total_amount}</span>
                            <span>Placed on ${new Date(res.created_at).toDateString()}</span>
                            <span style="text-transform: capitalize" >Payment Method: ${res.payment.pay_method_name}</span>
                            <span>Ship to: ${res.customer.first_name}</span>
                        </div>
                        <table class="table orderTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>`

                            res.order_items.map(function (item) {
                               html += `<tr>
                                    <td><p><img src="images/cart-item1.jpg" alt="">${item.product.product_name}</p></td>
                                    <td><p>$ ${item.product_per_price}</p></td>
                                    <td><p>${item.product_qty}</p></td>
                                    <td><p>$ ${item.product_per_price*item.product_qty}</p></td>
                                    <td>
                                    <form action="{{ url('cart/store') }}/${item.product.id}" method="POST" id="cart-form">
                                        {{ csrf_field() }}
                                    <button type="submit" href="#" class="btnStyle">Reorder</button>
                                    </form>
                                    </td>
                                   </tr>`
                            });
                           html += `</tbody>
                                    </table>`;
                        $('#OrderDetailModalBody').html(html);
                        $('#OrderDetailModal').modal('show');

                    }
                })
            });


            //Edit Address
            $('.editAddress').on('click',function () {
                $id = $(this).data('id');

                $.ajax({
                    url: "{{url('user/getAddressDetail')}}/"+$id,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res){
                        console.log(res);

                        $('#add_id').val(res.customer_address.id);
                        $('#first_name_e').val(res.customer_address.first_name);
                        $('#last_name_e').val(res.customer_address.last_name);
                        $('#phone_no_e').val(res.customer_address.phone_no);
                        $("#phone_no_e").intlTelInput("setNumber", res.customer_address.phone_no);
                        $('#title_e').val(res.customer_address.title);
                        $('#address_e').val(res.customer_address.address);
                        $("#city_e").append(new Option(res.city.name, res.city.id));

                        $('#company_name_e').val(res.customer_address.company_name);
                        $('#zip_code_e').val(res.customer_address.zip_code);
                        $('#country_e').val(res.customer_address.country);
                        // $('#country_e option[value="res.country"]').attr("selected", "selected");


                        $("#state_e").append(new Option(res.state.name, res.state.id));
                        $('#shipping_billing_e').val(res.customer_address.shipping_billing);

                        $('#editAddressModal').modal('show');

                    }
                })
            });
            
        });
    </script>
    <!-- <script>
        

        $('document').ready(function () {

            $("#country_e").on("change", function(ev) {
                var countryId = $(this).val();
                if(countryId != ''){
                    $("#state_e option").remove();
                    $("#cities_e option").remove();

                    $.ajax({
                        url: '{{url("user/getStates")}}/countryId/'+countryId,
                        type: 'get',
                        dataType: 'json',
                        success: function (data) {
                            if(data.tp == 1){
                                $.each(data['result'], function(key, val) {
                                    var option = $('<option />');
                                    option.attr('value', val.id).text(val.name);
                                    $('#state_e').append(option);
                                });
                                $("#state_e").prop("disabled",false);
                            }
                            else{
                                alert(data.msg);
                            }
                        }
                    });
                }
                else{
                    $("#state_e option").remove();
                }
            });

            $("#state_e").on("change", function(ev) {
                var stateId = $(this).val();
                if(stateId != ''){
                    $("#cities_e option").remove();

                    $.ajax({
                        url: '{{url("user/getCities")}}/stateId/'+stateId,
                        type: 'get',
                        dataType: 'json',
                        success: function (data) {
                            if(data.tp == 1){
                                $.each(data['result'], function(key, val) {
                                    var option = $('<option />');
                                    option.attr('value', val.id).text(val.name);
                                    $('#city_e').append(option);
                                });
                                $("#city_e").prop("disabled",false);
                            }
                        }
                    });
                }
                else{
                    $("#states_e option:gt(0)").remove();
                }
            });

            $(".formStyle").submit(function() {
                $("#country_code").val($("#phone_no").intlTelInput("getSelectedCountryData").dialCode);
            });

            $("#addAddressForm").submit(function() {
                $("input[name='phone_no_code']").val($("input[name='add_phone_no']").intlTelInput("getSelectedCountryData").dialCode);
            });

            $("#updateAddress").submit(function() {
                $("input[name='phone_no_code_e']").val($("#phone_no_e").intlTelInput("getSelectedCountryData").dialCode);
            });
        })*/
    </script> -->
@endsection
