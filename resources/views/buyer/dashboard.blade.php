@extends('seller.layout.app')
@section('title', 'Dashboard')
@section('content')
    <style>
        .orderDetialsModal {
            max-width: 90%;
        }

    </style>
    <section class="accountDetail">

        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <ul class="nav" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
                                aria-controls="details" aria-selected="true">Account Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="favorit-tab" data-toggle="tab" href="#favorit" role="tab"
                                aria-controls="favorit" aria-selected="false">Favorite Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="addresses-tab" data-toggle="tab" href="#addresses" role="tab"
                                aria-controls="addresses" aria-selected="false">Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab"
                                aria-controls="order" aria-selected="false">Order History</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <h2 class="title">Account Details</h2>
                            <form action="{{ url('buyer/updateAccountInformation') }}" class="row formStyle"
                                method="post">
                                @csrf
                                <div class="col-md-6">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" placeholder="Robert" name="name"
                                        value="{{ Auth::user()->buyer->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" disabled placeholder="Email Address"
                                        name="email" value="{{ Auth::user()->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Phone Number</label>
                                    <input type="tel" class="form-control" placeholder="4153477333" name="phone_number"
                                        value="{{ Auth::user()->buyer->phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Zip Code</label>
                                    <input type="text" class="form-control" placeholder="94112" name="zip_code"
                                        value="{{ Auth::user()->buyer->zip_code }}">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="orangeBtn">Save Changes</button>
                                    <a href="#" data-toggle="modal" data-target="#exampleModalCenter"
                                        class="orangeBtn chng-btn">Change Password</a>
                                </div>
                            </form>
                        </div>

                        <!-- search  wishlist products -->

                        <div class="tab-pane fade favoriteTab" id="favorit" role="tabpanel" aria-labelledby="favorit-tab">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form class="input-group searchStyle" id="searchWishlistProducts">
                                        <div class="input-group-prepend">
                                            <button class="input-group-text"><i class="fal fa-search"></i></button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Search Products" id="keyword"
                                            aria-label="Search Products" aria-describedby="">
                                    </form>
                                </div>
                            </div>
                            <!-- fetch products from products table -->
                            <div id="productArea" class="row">
                                @forelse($whishlist_products as $products)
                                    <div class="col-md-6">
                                        <div class="proThumb vertiThumb" id="vertiThub_{{ $products->product->id }}">
                                            <a href="{{ url('product-details') . '/' . $products->id }}">
                                                <img src="{{ url('/') }}/uploads/products/{{ $products->product->product_image }}"
                                                    alt="">
                                            </a>
                                            <div class="content">
                                                <h4><a
                                                        href="{{ url('product-details') . '/' . $products->id }}">{{ $products->product->product_name }}</a>
                                                </h4>
                                                <h5>Category: {{ $products->product->category->name }}
                                                    <div class="opt">
                                                        <a href="javascript:void(0)"
                                                            data-product="{{ $products->product->id }}"
                                                            data-wishlist='{{ $products->product->whishlist->id }}'
                                                            class="addBtn  remove_to_wishlist"
                                                            data-buyer="{{ Auth::user()->buyer->id ?? 0 }}">
                                                            <span class="remove ">
                                                                <i class="fa fa-trash "></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </h5>
                                                <span> {{ $products->product->product_current_price }}</span>
                                                <small> {{ $products->product->countryName->name }} </small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <h5 class="text-center">No Favorite Product yet!</h5>
                                @endforelse
                            </div>

                        </div>
                        <div class="tab-pane fade addressTab" id="addresses" role="tabpanel"
                            aria-labelledby="addresses-tab">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <h2 class="title">Address
                                        @if (count($addresses) == 0)
                                            <a href="javascript:void(0)" class="orangeBtn text-capitalize  px-4"
                                                @if (count($addresses) > 0) disabled="" @else data-toggle="modal" data-target="#addAddress" @endif>
                                                <i class="fa fa-plus"></i> Add an Address
                                            </a>
                                        @endif
                                    </h2>
                                </div>
                                @forelse($addresses as $addresse)
                                    <div class="col-lg-6">
                                        <div class="adressCard">
                                            <!-- fetching addresses -->
                                            <address>
                                                <span
                                                    class="tag mb-3 text-muted">{{ $addresse->shipping_billing }}</span>
                                                <span>{{ $addresse->first_name }} {{ $addresse->last_name }}</span>
                                                <span>{{ $addresse->address }}</span>
                                                <span>{{ $addresse->cityName->name }}</span>
                                                <span>{{ $addresse->stateName->name }}</span>
                                                <span>{{ $addresse->countryName->name }}</span>
                                                <span>{{ $addresse->phone_no }}</span>
                                            </address>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editAddress"
                                                class="edit editAddress" data-id="{{ $addresse->id }}"><i
                                                    class="fa fa-pencil"></i></a>
                                            <a href="#" class="remove removeAddress" data-id="{{ $addresse->id }}"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <h5>No Address Saved yet!</h5>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade addProducts" id="order" role="tabpanel" aria-labelledby="order-tab">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="title">Order History</h2>
                                </div>
                                <div class="col-lg-12">
                                    <form class="searchStyle form-inline align-items-end border-0" id="searchOrderForm">
                                        <div class="input-group col-md-12 my-2">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="input-group-text"><i
                                                        class="fal fa-search"></i></button>
                                            </div>
                                            <input type="text" class="form-control" name="order_product"
                                                id="order_product" placeholder="Search Products"
                                                aria-label="Search Products" aria-describedby="">
                                        </div>
                                        <div class="input-group col-md-6">
                                            <label for="status" class="d-block w-100">From:</label>
                                            <input type="date" class="form-control" placeholder="Search Products"
                                                name="order_from_date" id="order_from_date" aria-label="Search Products"
                                                aria-describedby="">
                                        </div>
                                        <div class="input-group col-md-6">
                                            <label for="status" class="d-block w-100">To:</label>
                                            <input type="date" class="form-control" placeholder="Search Products"
                                                name="order_to_date" id="order_to_date" aria-label="Search Products"
                                                aria-describedby="">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12">
                                    <div class="___class_+?78___">
                                        <table class="table orderTable">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Order ID</th>
                                                    {{-- <th>Product name</th> --}}
                                                    {{-- <th>Product Category</th> --}}
                                                    <th>Total Amount</th>
                                                    {{-- <th>Seller Name</th> --}}
                                                    <th>Status</th>
                                                    {{-- <th>Seller Number</th> --}}
                                                    <th>Order Date</th>
                                                    {{-- <th>Status</th> --}}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="orderArea">
                                                @php
                                                    $counter = 0;
                                                @endphp
                                                @forelse ($orders as $order)
                                                    @php
                                                        $counter++;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <p>{{ $counter }}</p>
                                                        </td>
                                                        <td>
                                                            <p>{{ $order->order_no }}</p>
                                                        </td>
                                                        {{-- <td>
                                                            <p>{{ $order->orderItems[0]->product->product_name }}</p>
                                                        </td>
                                                        <td>
                                                            <p>{{ $order->orderItems[0]->product->category->name }}</p>
                                                        </td> --}}
                                                        <td>
                                                            <p>{{ presentPrice($order->total_amount) }}</p>
                                                        </td>
                                                        {{-- <td>
                                                            <p>
                                                                {{ $order->seller->name }}
                                                            </p>
                                                        </td> --}}
                                                        <td>
                                                            <div class="alert alert-success alrtCustom" role="alert">
                                                                {{ strtoupper($order->order_status) }}
                                                            </div>
                                                            {{-- <p></p> --}}
                                                        </td>
                                                        {{-- <td>
                                                            <p>{{ $order->seller->phone_number }}</p>
                                                        </td> --}}
                                                        <td>
                                                            <p>{{ date('d-m-Y', strtotime($order->created_at)) }}</p>
                                                        </td>
                                                        {{-- <td>
                                                            <div class="alert alert-success alrtCustom" role="alert">
                                                                Received
                                                            </div>
                                                        </td> --}}
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-primary dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="fas fa-cog"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                                        onclick="viewOrderDetails('{{ $order->id }}')">
                                                                        View Details
                                                                    </a>
                                                                    {{-- <a class="dropdown-item" href="javascript:void(0)"
                                                                        id="deleteOrder"
                                                                        onclick="BuyerDeleteOrder($order->id)">Delete</a> --}}
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td>No Order Yet!</td>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header modal-top">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" id="changePassword" method="post">
                    <div class="modal-body modal-chnge">
                        <div class="alert alert-primary alert-dismissible fade show alSuccess" role="alert"
                            style="display:none">
                            <strong>Success!</strong> Password has been updated successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show alDanger" role="alert"
                            style="display:none">
                            <strong>Opps!</strong> Unexpected error occured please check errors below.
                            <span id="opps_span"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input class="form-control mb-3" type="password" Placeholder="Current Password"
                            name="current_password" required />
                        <small class="text-danger errorField" id="currentPassErrorField"
                            style="display: block;margin-left: 5px;margin-top: -5px;"></small>
                        <input class="form-control mb-3" type="password" Placeholder="New Password" name="password"
                            required />
                        <small class="text-danger errorField" id="newPassErrorField"
                            style="display: block;margin-left: 5px;margin-top: -5px;"></small>
                        <input class="form-control mb-3" type="password" Placeholder="Confirm Password"
                            name="password_confirmation" required />
                        <small class="text-danger errorField" id="passConfErrorField"
                            style="display: block;margin-left: 5px;margin-top: -5px;"></small>
                    </div>
                    <div class="modal-footer modal-last">
                        <button type="submit" class="orangeBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddress" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">

                    <form action="{{ url('buyer/addCustomerAddress') }}" method="post" class="row formStyle"
                        id="addAddressForm">
                        @csrf
                        <div class="col-md-12">
                            <h3>Add Address</h3>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="tel" class="form-control phone_no" name="add_phone_no" id="add_phone_no"
                                    placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Company Name" name="company_name">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Street Line" name="address1" required>
                        </div>
                        {{-- <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Address2" name="address2" required>
                        </div> --}}
                        <div class="col-md-6">
                            <select class="form-control country" name="country" id="countryId" required>
                                <option value="country">Select Country</option>
                                @foreach ($countries as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <select name="state" id="stateId" class="form-control state" required>
                                <option value="State">Select State</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control city" name="city" id="cityId" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Zip Code" name="zip_code" required>
                        </div>
                        <div class="col-md-12">
                            <button class="orangeBtn updateOrangeBtn">Create Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddress" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">

                    <form action="{{ url('buyer/updateCustomerAddress') }}" method="post" class="row formStyle"
                        id="editAddressForm">
                        @csrf
                        <input type="hidden" name="id" id="address_id" value="" />
                        <div class="col-md-12">
                            <h3>Edit Address</h3>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="First Name" name="first_name"
                                id="first_name_e" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                id="last_name_e" required>
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Enter Email Address" name="email"
                                id="email_e" required>
                        </div>

                        <div class="col-md-6">

                            <div class="input-group">
                                <input type="tel" class="form-control phone_no" name="phone_no" id="phone_no_e"
                                    placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Company Name" name="company_name"
                                id="company_name_e">
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Street Line" name="address1"
                                id="address_1_e" required>
                        </div>

                        {{-- <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Address2" name="address2" id="address_2_e"
                            required>
                    </div> --}}

                        <div class="col-md-6">
                            <select class="form-control country" name="country" id="countryId1" required>
                                <option value="country">Select Country</option>
                                @foreach ($countries as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="state" id="stateId1" class="form-control state" required>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control city" name="city" id="cityId1" required></select>
                        </div>

                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Zip Code" name="zip_code"
                                id="zip_code_e" required>
                        </div>
                        <div class="col-md-12">
                            <button class="orangeBtn updateOrangeBtn">Update Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade " id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog orderDetialsModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="getFeaturedModalLongTitle">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetails">
                </div>
                {{-- <div class="modal-footer text-right">
                    <button class="orangeBtn">Mark as Received</button>
                </div> --}}
            </div>
        </div>
        <!-- Modal End -->
    @endsection

    @section('extra-js')
        <script>
            $(document).ready(function() {

                $('#changePassword').submit(function(evt) {
                    evt.preventDefault();

                    var formData = new FormData();
                    formData.append('current_password', $('input[name=current_password]').val());
                    formData.append('password', $('input[name=password]').val());
                    formData.append('password_confirmation', $('input[name=password_confirmation]').val());
                    formData.append('_token', $('input[name=_token]').val());
                    //$(this).attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('buyer/update_password') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            //Clearing all errors
                            // console.log(data);
                            $('.errorField').css('display', 'none');
                            if (!data.status) {
                                $(".alDanger").show();
                                //$(".addEvent").find('i').hide();
                                //$(".addEvent").attr('disabled', false);
                                if (Object.keys(data.errors).length > 0) {

                                    if (typeof data.errors.current_password != 'undefined') {
                                        $('#currentPassErrorField').text(data.errors
                                            .current_password[0]);
                                        $('#currentPassErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.password != 'undefined') {
                                        $('#newPassErrorField').text(data.errors.password[0]);
                                        $('#newPassErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.password_confirmation != 'undefined') {
                                        $('#passConfErrorField').text(data.errors
                                            .password_confirmation[0]);
                                        $('#passConfErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.errors != 'undefined') {
                                        $('#opps_span').text(data.errors.errors);
                                        $('#opps_span').css('display', 'block')
                                    }

                                }
                            } else {
                                $(".alSuccess").show();
                                $(".alDanger").hide();
                                // Clear Data
                                $("input[name=current_password]").val("");
                                $("input[name=password]").val("");
                                $("input[name=password_confirmation]").val("");
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                                //   window.location = "{{ url('admin/events') }}";
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                });

                // get states by countries
                $('.country').on('change', function() {
                    var country = $(this).val();
                    // console.log(country);
                    $.ajax({
                        type: "get",
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        url: "{{ url('buyer/getStates/countryId/') }}/" + country,
                        data: {},
                        success: function(data) {
                            if (country != null) {
                                var html = "";
                                $.each(data.response_data, function(index, obj) {
                                    html += "<option value='" + obj.id + "'>" + obj.name +
                                        "</option>"
                                })
                                $('.state').html(html);
                                $('.state').trigger('change');
                            }
                        },
                    });

                });


                // get cities by states
                $('.state').on('change', function() {
                    var state = $(this).val();
                    //stateID=1
                    // console.log(state);
                    $.ajax({
                        type: "get",
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        url: "{{ url('buyer/getCities/stateId/') }}/" + state,
                        data: {},
                        // getCities/stateId/{id}
                        success: function(data) {
                            if (country != null && state != null) {
                                var html = "";
                                $.each(data.response_data, function(index, obj) {
                                    html += "<option value='" + obj.id + "'>" + obj.name +
                                        "</option>"
                                })

                                $('.city').html(html);
                            }

                        },

                        error: function() {},
                        complete: function() {}
                    });

                });

                $('.state').on('change', function() {
                    var state = $(this).val();
                    // console.log(state);
                    $.ajax({
                        type: "get",
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        url: "{{ url('buyer/getCities/stateId/') }}/" + state,
                        data: {},
                        // getCities/stateId/{id}
                        success: function(data) {
                            var html = "";
                            $.each(data.response_data, function(index, obj) {
                                html += "<option value='" + obj.id + "'>" + obj.name +
                                    "</option>"
                            })
                            $('.city').html(html);
                        },

                        error: function() {},
                        complete: function() {}
                    });
                    // second ajax call
                    $.ajax({
                        type: "get",
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        url: "{{ url('buyer/getCities/stateId/') }}/" + state,
                        data: {},
                        // getCities/stateId/{id}

                        success: function(data) {
                            var html = "";
                            $.each(data.response_data, function(index, obj) {
                                html += "<option value='" + obj.id + "'>" + obj.name +
                                    "</option>"
                            })
                            $('.city').html(html);
                        },

                        error: function() {},
                        complete: function() {}
                    });
                });
            });

            //Edit Address
            $('.editAddress').on('click', function() {

                $id = $(this).data('id');

                $("#address_id").val($id);

                $.ajax({
                    url: "{{ url('buyer/getAddressDetail') }}/" + $id,

                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // console.log(res);

                        // $('#add_id').val(res.customer_address.id);
                        $('#first_name_e').val(res.customer_address.first_name);

                        $('#last_name_e').val(res.customer_address.last_name);
                        $('#email_e').val(res.customer_address.email);

                        $('#phone_no_e').val(res.customer_address.phone_no);
                        $('#address_1_e').val(res.customer_address.address1);
                        $('#address_2_e').val(res.customer_address.address2);
                        $("#cityId1").append(new Option(res.city.name, res.city.id));

                        $('#company_name_e').val(res.customer_address.company_name);
                        $('#zip_code_e').val(res.customer_address.zip_code);

                        $('#countryId1').val(res.customer_address.country);
                        $('#country_e option[value="res.country"]').attr("selected", "selected");


                        $("#stateId1").append(new Option(res.state.name, res.state.id));

                        $('#shipping_billing_e').val(res.customer_address.shipping_billing);
                        //$('#shipping_billing_e option[value="res.shipping_billing"]').attr("selected", "selected");

                        $('#editAddressModal').modal('show');

                    }
                })
            });

            //remove address
            $('.removeAddress').on('click', function() {

                $id = $(this).data('id');
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        url: "{{ url('buyer/deleteAddress') }}/" + $id,
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(res) {
                            window.location.href = "{{ url('buyer/dashboard') }}";
                        }
                    });
                }


            });

            $(document).on('click', ".remove_to_wishlist", function() {
                // console.log(">>")
                let el = $(this);
                let divId = "";
                $.post(base_url + '/buyer/add-wishlist', {
                    "product_id": el.data('product'),
                    "buyer_id": el.data('buyer'),
                    "_token": $("meta[name=csrf-token]").attr('content'),
                    "remove": 1,
                    "wishlist_id": el.attr('data-wishlist')
                }, function(d) {
                    if (d.status) {
                        // divId = el.parent().parent().parent().parent();
                        console.log(el.data('product'));
                        $('#vertiThub_' + el.data('product')).parent().delay(1000).fadeOut();
                        toastr.success(d.message, 'Success!');

                        return
                        // el.removeClass('remove_to_wishlist');
                        // el.removeClass('wishlist-added');
                        // el.addClass('add_to_wishlist')
                        // el.css('color', '');
                        // location.reload();
                    } else {
                        toastr.error(d.message, 'Error!')
                    }
                });

            })


            // search buyer favorite products 

            $('#searchWishlistProducts').on('submit', function(e) {
                e.preventDefault();
                let keyword = $('#keyword').val();
                if (keyword.length == 0) {
                    return false;
                }

                $.ajax({
                    url: "{{ url('buyer/searchWishlistProduct') }}",
                    type: "Get",
                    data: {
                        keyword: keyword
                    },
                    success: function(data) {
                        $('#productArea').html('');
                        if (data.length > 0 && data.product !== null) {
                            $.each(data, function(index, product) {
                                $('#productArea').append(`
                                    <div class="col-md-6">
                                        <div class="proThumb vertiThumb" id="vertiThumb_${product.id}" >
                                            <a href="{{ url('/') }}/product-details/${product.id}">
                                                <img src="{{ url('/') }}${get_image_path(product.product.product_image)}" alt="">
                                            </a>
                                            <div class="content">
                                                <h4><a href="{{ url('/') }}/product-details/${product.id}">${ product.product.product_name }</a></h4>
                                                <h5>Category: ${product.product.category.name }
                                                    <div class="opt">
                                                    <a href="javascript:void(0)" data-product="${product.product.id}"  data-wishlist='${product.product.whishlist.id }' class="addBtn  remove_to_wishlist" data-buyer="{{ Auth::user()->buyer->id ?? 0 }}">
                                                        <span class="remove "><i class="fa fa-trash "></i></span>
                                                    </a>
                                                    </div>
                                                </h5>
                                                <span> ${product.product.product_current_price}</span>
                                                <small> ${product.product.country_name.name } </small>                                    
                                            </div>
                                        </div>
                                    </div>
                              `);
                            })
                        } else {
                            $('#productArea').append(
                                `<div class="col-md-12"><h5>No Product yet in Favorites!</h5></div>`);
                        }

                    }
                })
            });

            function formatDate(input) {
                var datePart = input.match(/\d+/g),
                    year = datePart[0].substring(2), // get only two digits
                    month = datePart[1],
                    day = datePart[2];

                return day + '-' + month + '-' + year;
            }

            // Search Orders
            $('#searchOrderForm').on('submit', function(e) {
                e.preventDefault();
                let order_product = $('#order_product').val();
                let from_date = $('#order_from_date').val();
                let to_date = $('#order_to_date').val();

                if (order_product.length == 0 && from_date.length == 0 && to_date.length == 0) {
                    return;
                }

                if (from_date !== "" && to_date == "") {
                    alert('Please Select To Date');
                    return;
                }
                if (to_date !== "" && from_date == "") {
                    alert('Please Select From Date');
                    return
                }

                if (Date.parse(from_date) > Date.parse(to_date)) {
                    alert("Start date should be less than end date");
                    return false;
                }


                $.ajax({
                    url: "{{ url('buyer/searchBuyerOrders') }}",
                    type: "Get",
                    data: {
                        order_product: order_product,
                        from_date: from_date,
                        to_date: to_date
                    },
                    success: function(data) {
                        $('#orderArea').html('');
                        let html = '';
                        let counter = 0;
                        if (data.length > 0) {
                            console.log(data);
                            $.each(data, function(index, order) {
                                counter++;
                                html += `<tr>
                                            <td>
                                                <p>${ counter }</p>
                                            </td>
                                            <td>
                                                <p>${ order.order_no }</p>
                                            </td>
                                            <td>
                                                <p>${ order.order_items[0].product.product_name }</p>
                                            </td>
                                            <td>
                                                <p>${ order.order_items[0].product.category.name }</p>
                                            </td>
                                            <td>
                                                <p>${ order.total_amount }</p>
                                            </td>
                                            <td>
                                                <p>${ order.seller.name }</p>
                                            </td>
                                            <td>
                                                <p>${ order.order_status.toUpperCase() }</p>
                                            </td>
                                            <td>
                                                <p>${ order.seller.phone_number }</p>
                                            </td>
                                            <td>
                                                <p>${ formatDate(order.created_at) }</p>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-info round">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>`;
                            })

                            $('#orderArea').html(html);
                        } else {
                            $('#orderArea').append(`<tr><td><p>No Order Yet!</p></td></tr>`);
                        }

                    }
                })


            });

            // get image path 

            function get_image_path(img) {

                var path = '/images/placeholder_img.jpg';

                if (img != null) {
                    // echo 1;
                 

                    path = '/uploads/products/' + img ? '/uploads/products/' + img : path;
                    return path;
                } else {
                    return path;
                }
            }


            //VIEW ORDER DETAILS
            function viewOrderDetails(id, status) {
                $.ajax({
                    type: "get",
                    url: "{{ url('buyer/getOrderDetails') }}/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data == 0) {
                            toastr.error('Order not Found!');
                        } else {
                            $('#orderDetails').html(data.html);
                            // $('#orderDetails').html(html);
                            $('#orderDetailsModal').modal('show');
                        }
                    },
                    error: function(err) {
                        alert(err.responseJSON.errors);
                    }
                })
            }
        </script>
    @endsection
