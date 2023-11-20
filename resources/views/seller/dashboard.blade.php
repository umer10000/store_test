@extends('seller.layout.app')
@section('title', 'Dashboard')
@section('content')
    <style>
        .custom-file {
            height: 100px;
            width: 400px;
        }

        .orderDetialsModal {
            max-width: 75%;
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
                            <a class="nav-link" @if (count($addresses) > 0)
                                href="#order" id="order-tab" data-toggle="tab" role="tab" aria-controls="order"
                            aria-selected="false" @else disabled="" style="cursor: not-allowed;
                                pointer-events: all !important;" @endif>My Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" @if (count($addresses) > 0)
                                href="#favorit" id="favorit-tab" data-toggle="tab" role="tab" aria-controls="favorit"
                            aria-selected="false" @else disabled="" style="cursor: not-allowed;pointer-events: all
                                !important;" @endif>Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" @if (count($addresses) > 0)
                                href="#archivee" id="archivee-tab" data-toggle="tab" role="tab"
                            aria-controls="archivee" aria-selected="false" @else disabled=""
                                style="cursor:not-allowed;pointer-events: all !important;" @endif
                                >Archive Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" @if (count($addresses) > 0)
                                href="#featured" id="featured-tab" data-toggle="tab" role="tab"
                            aria-controls="featured" aria-selected="false" @else disabled=""
                                style="cursor:not-allowed;pointer-events: all !important;" @endif>Get
                                Featured</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="addresses-tab" data-toggle="tab" href="#addresses" role="tab"
                                aria-controls="addresses" aria-selected="false">Address</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <h2 class="title">Account Details</h2>
                            @if (count($addresses) <= 0)
                                <p class="text-left"><strong>Note:</strong> In order to start setting up your store
                                    please add your address first.</p>
                            @endif
                            <form action="{{ url('seller/updateAccountInformation') }}" class="row formStyle"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="coverImg"
                                        style="background: url('{{ sellerCoverPicture(@Auth::user()->seller->cover_img) }}') center/cover no-repeat">
                                        <div class="avatar-upload">
                                            <div class="avatar-edit">
                                                <input type='file' id="imageUpload" name="profile_picture"
                                                    accept=".png, .jpg, .jpeg" />
                                                <label for="imageUpload"></label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview"
                                                    style="background-image: url('{{ sellerProfilePicture(@Auth::user()->seller->profile_picture) }}');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="file-input">
                                            <input type="file" name="cover_img" id="file-input" class="file-input__input" />
                                            <label class="file-input__label" for="file-input">
                                                <i class="fa fa-pencil"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" placeholder="Robert" name="name"
                                        value="{{ Auth::user()->seller->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" disabled placeholder="Email Address"
                                        value="{{ Auth::user()->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number" placeholder="4153477333"
                                        value="{{ Auth::user()->seller->phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Zip Code</label>
                                    <input type="text" class="form-control" placeholder="94112" name="zip_code"
                                        value="{{ Auth::user()->seller->zip_code }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="">About</label>
                                    <textarea rows="6" class="form-control" placeholder="Write about Yourself"
                                        name="about">{{ Auth::user()->seller->about }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="orangeBtn">Save Changes</button>
                                    <a href="#" data-toggle="modal" data-target="#exampleModalCenter"
                                        class="orangeBtn chng-btn">Change Password</a>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade addProducts" id="order" role="tabpanel" aria-labelledby="order-tab">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="title">My Products
                                        <a href="javascript:void(0)" class="orangeBtn" data-toggle="modal"
                                            data-target="#addProducts">
                                            <i class="fa fa-plus"></i>
                                            Add Product
                                        </a>
                                    </h2>
                                </div>
                                <div class="col-lg-12">

                                    <form class="input-group searchStyle" id="searchSellerProducts">
                                        <div class="input-group-prepend">
                                            <button type="submit" class="input-group-text">
                                                <i class="fal fa-search"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Search Products" id="keyword"
                                            aria-label="Search Products" aria-describedby="">
                                    </form>
                                    <p><strong>Note:</strong> A 10% Platform Fee will be Added to your Final Price to the
                                        Customer.</p>
                                </div>
                            </div>
                            <div id="productArea" class="row">
                                @forelse($products as $product)
                                    <div class="col-md-6">

                                        <div class="proThumb vertiThumb">
                                            @if (in_array($product->id, $specialDealsProducts))
                                                <a href="javascript:;" class="getFeaturedBtn">Already
                                                    Featured</a>
                                            @else
                                                <a href="javascript:;" class="getFeaturedBtn"
                                                    onclick="markAsSpeical('{{ $product->id }}')">Get
                                                    Featured</a>
                                            @endif

                                            <a href="{{ url('product-details') . '/' . $product->id }}">
                                                <img src="{{ productImage(@$product->product_image) }}" alt="" style="">
                                            </a>
                                            <div class="content">
                                                <h4>
                                                    <a
                                                        href="{{ url('product-details') . '/' . $product->id }}">{{ $product->product_name }}</a>
                                                </h4>

                                                <h4>Category: {{ $product->category->name }}
                                                    <div class="opt">
                                                        <span class="___class_+?63___" title="Remove Product"
                                                            onclick="removeProduct({{ $product->id }})"
                                                            style="cursor: pointer;" data-id="{{ $product->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </span>
                                                        <span class="edit editProduct" data-id="{{ $product->id }}"
                                                            title="Edit Product">
                                                            <i class="fa fa-edit"></i>
                                                        </span>
                                                        <a href="javascript:void(0)" style="color: lightgray"
                                                            title="Clone Product" class="cloneProduct"
                                                            data-id="{{ $product->id }}">
                                                            <i class="fas fa-clone"></i>
                                                        </a>
                                                    </div>
                                                </h4>

                                                <span>
                                                    @if ($product->discount_price > 0)
                                                        @php
                                                            $final_price_pecnt_amnt = ($product->discount_price / 100) * $setting->service_charges;
                                                            $final_price = $product->discount_price + $final_price_pecnt_amnt;
                                                        @endphp
                                                        {{ presentPrice($final_price) }}
                                                    @else
                                                        @php
                                                            $final_price_pecnt_amnt = ($product->product_current_price / 100) * $setting->service_charges;
                                                            $final_price = $product->product_current_price + $final_price_pecnt_amnt;
                                                        @endphp
                                                        {{ presentPrice($final_price) }}
                                                    @endif
                                                    {{-- ${{ $product->product_current_price }} --}}
                                                </span>
                                                <small>{{ Str::limit($product->description, 100, $end = '...') }}</small>
                                                @if ($product->deleted_at != null)
                                                    <span class="badge badge-danger" style="color: #fff">Inactive</span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <h5>No Product yet!</h5>
                                @endforelse
                            </div>

                        </div>
                        <div class="tab-pane fade favoriteTab" id="favorit" role="tabpanel" aria-labelledby="favorit-tab">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="title">Orders</h2>
                                </div>
                                {{-- <div class="col-lg-12">
                                    <form class="searchStyle form-inline align-items-end border-0" id="searchOrderForm">
                                        <div class="input-group col-md-12 my-2">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="input-group-text"><i
                                                        class="fal fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="___class_+?72___">
                                        <table class="table orderTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Order ID</th>
                                                    {{-- <th>Product name</th>
                                                    <th>Product Category</th> --}}
                                                    <th>Buyer Name</th>
                                                    {{-- <th>Order Amount</th> --}}
                                                    <th>Order Date</th>
                                                    <th>Status</th>
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
                                                            <p>
                                                                @if ($order->buyer !== null)
                                                                    {{ $order->buyer->name }}
                                                                @else
                                                                    {{ $order->buyer_name }}
                                                                @endif
                                                            </p>
                                                        </td>
                                                        {{-- <td>
                                                            <p>
                                                                {{ presentPrice($order->total_amount) }}
                                                            </p>
                                                        </td> --}}
                                                        <td>
                                                            <p>
                                                                {{ date('d-m-Y', strtotime($order->created_at)) }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>{{ strtoupper($order->order_status) }}</p>
                                                        </td>
                                                        <td>
                                                            <!-- Example single danger button -->
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-primary dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="fas fa-cog"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onclick="viewOrderDetails('{{ $order->id }}')">View
                                                                        Details</a>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        id="archive"
                                                                        onclick="archiveOrder('{{ $order->id }}','yes')">Archive</a>
                                                                    {{-- <a class="dropdown-item" href="javascript:void(0)"
                                                                    id="changeStatus">Change Status</a> --}}
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        id="deleteOrder"
                                                                        onclick="sellerDeleteOrder('{{ $order->id }}')">Delete</a>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onclick="editOrderTrackingNo('{{ $order->id }}')">Share
                                                                        Tracking Code</a>
                                                                    {{-- <div class="dropdown-divider"></div> --}}

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <h5>No Order Yet!</h5>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade archivee" id="archivee" role="tabpanel" aria-labelledby="archivee-tab">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="title">Archive Orders</h2>
                                </div>
                                <div class="col-md-12">
                                    <div class="___class_+?86___">
                                        <table class="table orderTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Order ID</th>
                                                    {{-- <th>Product name</th>
                                                    <th>Product Category</th> --}}
                                                    <th>Buyer Name</th>
                                                    {{-- <th>Order Amount</th> --}}
                                                    <th>Order Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="orderArea">
                                                @php
                                                    $counter = 0;
                                                @endphp
                                                @forelse ($archiveOrders as $order)
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
                                                            <p>
                                                                @if ($order->buyer !== null)
                                                                    {{ $order->buyer->name }}
                                                                @else
                                                                    {{ $order->buyer_name }}
                                                                @endif
                                                            </p>
                                                        </td>
                                                        {{-- <td>
                                                            <p>
                                                                {{ presentPrice($order->total_amount) }}
                                                            </p>
                                                        </td> --}}
                                                        <td>
                                                            <p>
                                                                {{ date('d-m-Y', strtotime($order->created_at)) }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>{{ strtoupper($order->order_status) }}</p>
                                                        </td>
                                                        <!-- Example single danger button -->
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-primary dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="fas fa-cog"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onclick="viewOrderDetails('{{ $order->id }}')">View
                                                                        Details</a>
                                                                    {{-- <a class="dropdown-item" href="javascript:void(0)"
                                                                    id="archive"
                                                                    onclick="archiveOrder('{{ $order->id }}','yes')">Archive</a> --}}
                                                                    {{-- <a class="dropdown-item" href="javascript:void(0)"
                                                                    id="changeStatus">Change Status</a> --}}
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        id="deleteOrder"
                                                                        onclick="sellerDeleteOrder($order->id)">Delete</a>
                                                                    {{-- <div class="dropdown-divider"></div> --}}

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td>
                                                            <h5>No Order Yet!</h5>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade featuredTab" id="featured" role="tabpanel" aria-labelledby="featured-tab">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="title">Get Featured</h2>
                                </div>
                                <div class="col-lg-12">
                                    <div class="featuredContent">
                                        <p>Do you want to get your banner featured on the homepage of K7 Store? Follow
                                            these
                                            steps below:</p>
                                        <ol class="p-0">
                                            <li>Upload a high-res banner that you would like to feature on K7 Store's
                                                homepage.<br><small>(Banner Dimensions: 790x439)</small><br>
                                                <div class="custom-file my-3">
                                                    <input type="file" name="banner" required
                                                        accept="image/png, image/jpg, image/jpeg" />
                                                </div>
                                            </li>
                                            <li>How long do you want your banner to be featured for?
                                                <ul class="p-0">
                                                    @forelse ($featuredPackages as $featuredPackage)

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="featured_id"
                                                                id="{{ $featuredPackage->id }}_featured"
                                                                value="{{ $featuredPackage->id }}">
                                                            <label class="form-check-label"
                                                                for="{{ $featuredPackage->id }}_featured">
                                                                {{ $featuredPackage->name }} Fee:
                                                                {{ '$' . $featuredPackage->amount }}
                                                            </label>
                                                            <input type="hidden" value="{{ $featuredPackage->amount }}"
                                                                id="amount_{{ $featuredPackage->id }}">
                                                        </div>
                                                    @empty

                                                    @endforelse
                                                </ul>
                                            </li>
                                        </ol>
                                        <a href="javascript:void(0)" class="orangeBtn" onclick="markAsFeatured()">Pay
                                            Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- address of seller --}}
                        <div class="tab-pane fade addressTab" id="addresses" role="tabpanel"
                            aria-labelledby="addresses-tab">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <h2 class="title">Address
                                        @if (count($addresses) == 0)
                                            <a href="javascript:void(0)" class="orangeBtn text-capitalize  px-4"
                                                data-toggle="modal" data-target="#addAddress">
                                                <i class="fa fa-plus"></i> Add an Address

                                        @endif
                                        </a>
                                    </h2>
                                    <p class="text-left"><strong>Note:</strong> Submit Your Address to Upload
                                        Products.</p>
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
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editAddressModal"
                                                class="edit editAddress" data-id="{{ $addresse->id }}"><i
                                                    class="fa fa-pencil"></i></a>
                                            <a href="javascript:void(0)" class="remove removeAddress"
                                                data-id="{{ $addresse->id }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <h5>No Address Saved yet!</h5>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                        {{-- end addresses of seller --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Button trigger modal -->

    <!-- Modal ChangePassword -->
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
                            <strong>Opps!</strong> Unexpected error occurred please check errors below.
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


    <!-- Add Product Modal -->
    <div class="modal fade" id="addProducts" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">
                    <div class="alert alert-primary alert-dismissible fade show adSuccess" role="alert"
                        style="display:none">
                        <strong>Success!</strong> Your Product Added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show adDanger" role="alert" style="display:none">
                        <strong>Opps!</strong> Unexpected error occured please check errors below.
                        <span id="opps_span"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('seller/add-product') }}" method="post" class="row formStyle" id="addProduct"
                        enctype="multipart/form-data">
                        <div class="col-md-12">
                            <h3>Add Product</h3>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Product Title" name="title" required>
                            <small class="text-danger errorField" id="titleErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="product_type" required>
                                <option value="">Product Type</option>
                                <option>Physical</option>
                                <option>Downloadable</option>
                            </select>
                            <small class="text-danger errorField" id="productTypeErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="qty" required id="qty" placeholder="Quantity"
                                min="0" />
                            <small class="text-danger errorField" id="qtyErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Price" name="price"
                                required min="0">
                            <small class="text-danger errorField" id="priceErrorField" style="display: none"></small>
                            <input type="text" class="form-control" disabled id="final_price"
                                style="background:lightgray" placeholder="Price after Platform Fee Added">
                            <strong class="d-block mb-4" style="font-size: 13px">
                                NOTE: A 10% Platform Fee will be Added
                                to your Final Price to the Customer.
                            </strong>

                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Discounted Price"
                                name="discount_price" min="0">
                            <small class="text-danger errorField" id="discountPriceErrorField"
                                style="display: none"></small>
                            <strong class="d-block mb-4" style="font-size: 13px">NOTE: Leave this textfield blank if you
                                are
                                not offering any
                                discounts.</strong>
                        </div>
                        {{-- <div class="col-md-4">
                            <input type="number" step="any" class="form-control" placeholder="VAT" name="vat" min="0">
                            <small class="text-danger errorField" id="vatErrorField" style="display: none"></small>
                        </div> --}}
                        <div class="col-md-4">
                            <select class="form-control" name="shipping" required>
                                <option value="">Select Shipping</option>
                                <option value="1">Fedex</option>
                                <option value="2">Free Shipping</option>
                                <option value="3">Shipped By Seller</option>
                            </select>
                            <small class="text-danger errorField" id="shippingErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-4" id="ship_charges">
                            <input type="text" name="shipping_charges" class="form-control" value="0"
                                placeholder="Shipping Charges" disabled>
                            <small class="text-danger e_errorField" id="shipping_chargesErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="brand" required>
                                <option value="">Select Brand</option>
                                @forelse($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger errorField" id="brandErrorField" style="display: none"></small>
                        </div>
                        {{-- <div class="col-md-6">
                            <select class="form-control" name="shipping" required>
                                <option value="">Select Shipping</option>
                                <option value="">Free Shipping</option>
                                <option value="">Fedex</option>
                            </select>
                        </div> --}}

                        <div class="col-md-4">
                            <select class="form-control" name="category" id="main-category" required>
                                <option value="">Select Category</option>
                                @forelse($mainCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger errorField" id="categoryErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="sub_category" id="sub-category" required>
                                <option>Select Sub Category</option>
                            </select>
                            <small class="text-danger errorField" id="subCategoryErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="condition" required>
                                <option value="">Condition</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                            </select>
                            <small class="text-danger errorField" id="conditionErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-12">
                            <select class="form-control" name="location" id="locations" required>
                                <option value="">Select Location</option>
                                @forelse($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger errorField" id="locationErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Weight" name="weight"
                                value="" min="0">
                            <p class="lngthUnit">lbs</p>
                            <small class="text-danger errorField" id="weightErrorField" style="display: none"></small>
                        </div>
                        <!-- <div class="col-md-6">
                                                                                                            <select name="weight_unit" id="weight_unit" class="form-control">
                                                                                                                <option value="">Select Weight Unit</option>
                                                                                                                <option value="LB">LB</option>
                                                                                                                {{-- <option value="KG">KG</option> --}}
                                                                                                            </select>
                                                                                                            <small class="text-danger errorField" id="weightUnitErrorField" style="display: none"></small>
                                                                                                        </div> -->

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Length" name="length"
                                min="0" value="">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger errorField" id="lengthErrorField" style="display: none"></small>
                        </div>

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Width" name="width"
                                value="" min="0">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger errorField" id="widthErrorField" style="display: none"></small>
                        </div>

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Height" name="height"
                                min="0" value="">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger errorField" id="heightErrorField" style="display: none"></small>
                        </div>
                        <!-- <div class="col-md-6">
                                                                                                        <select name="dimensions_unit" id="dimensions_unit" class="form-control">
                                                                                                            <option value="">Select Dimensions Unit</option>
                                                                                                            {{-- <option value="CM">CM</option> --}}
                                                                                                            <option value="IN">INCH</option>
                                                                                                        </select>
                                                                                                        <small class="text-danger errorField" id="dimensionsErrorField" style="display: none"></small>
                                                                                                    </div> -->
                        <div class="col-md-12">
                            <textarea name="description" placeholder="Product Description" required class="form-control"
                                cols="30" rows="5"></textarea>
                            <small class="text-danger errorField" id="descriptionErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6 offset-5 mb-4" id="product_file_area" style="display:none">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="product_file" id="product_file"
                                    accept=".zip" id="product_file">
                                <label class="custom-file-label" for="validatedCustomFile"><i
                                        class="fa fa-cloud-upload"></i>Select Product File</label>
                            </div>
                            <small class="text-danger errorField" id="productFileErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-12 mb-4">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;height: 150px;">
                                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="" id="img_00"
                                                style="height: 150px;width: 200px;">
                                        </td>
                                        <td>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="product_picture"
                                                    id="product_picture_00" onchange="PreviewImage(this,'00')"
                                                    accept=".png, .jpg, .jpeg" id="product_picture" required>
                                                <label class="custom-file-label" for="validatedCustomFile">Select
                                                    Pictures...</label>
                                            </div>
                                            <small class="text-danger errorField" id="productPictureErrorField"
                                                style="display: none"></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 mb-4">
                            <h5 class="text-center">
                                Additional Images
                                <button type="button" class="orangeBtn" onclick="addMoreImgRow()">+</button>
                            </h5>
                        </div>
                        <div class="col-md-12 mb-4">
                            <table>
                                <tbody id="addMoreImgDiv"></tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="orangeBtn updateOrangeBtn  w-100" id="createProduct">Create
                                Product<i class="fa fa-spinner fa-spin" style="display: none;"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProducts" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">
                    <div class="alert alert-primary alert-dismissible fade show adeSuccess" role="alert"
                        style="display:none">
                        <strong>Success!</strong> Password has been updated successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show adeDanger" role="alert"
                        style="display:none">
                        <strong>Opps!</strong> Unexpected error occured please check errors below.
                        <span id="opps_span"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('seller/add-product') }}" method="post" class="row formStyle" id="addProduct"
                        enctype="multipart/form-data">
                        <div class="col-md-12">
                            <h3>Edit Product</h3>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Product Title" name="e_title" required>
                            <small class="text-danger e_errorField" id="e_titleErrorField" style="display: none"></small>
                            <input type="hidden" name="product_id">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="e_product_type" required>
                                <option value="">Product Type</option>
                                <option>Physical</option>
                                <option>Downloadable</option>
                            </select>
                            <small class="text-danger e_errorField" id="e_productTypeErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="e_qty" required id="e_qty"
                                placeholder="Quantity" min="0" />
                            <small class="text-danger errorField" id="e_qtyErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Price" name="e_price"
                                min="0" required>
                            <small class="text-danger e_errorField" id="e_priceErrorField" style="display: none"></small>
                            <input type="text" class="form-control" disabled id="e_final_price"
                                placeholder="Price after Platform Fee Added" style="background:lightgray">
                            <strong class="d-block mb-4" style="font-size: 13px">
                                NOTE: A 10% Platform Fee will be Added
                                to your Final Price to the Customer.
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <input type="number" class="form-control" placeholder="Discounted Price" step="any"
                                name="e_discount_price" min="0">
                            <strong class="d-block mb-4" style="font-size: 13px">NOTE: Leave this textfield blank if you
                                are
                                not offering any
                                discounts.</strong>
                            <small class="text-danger e_errorField" id="e_discountPriceErrorField"
                                style="display: none"></small>
                        </div>
                        {{-- <div class="col-md-4">
                            <input type="number" class="form-control" placeholder="VAT" step="any" name="e_vat" min="0">
                            <small class="text-danger e_errorField" id="e_vatErrorField" style="display: none"></small>
                        </div> --}}
                        <div class="col-md-4">
                            <select class="form-control" name="e_brand" required>
                                <option value="">Select Brand</option>
                                @forelse($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger e_errorField" id="e_brandErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="e_shipping" required>
                                <option value="">Select Shipping</option>
                                <option value="1">Fedex</option>
                                <option value="2">Free Shipping</option>
                                <option value="3">Shipped By Seller</option>
                            </select>
                            <small class="text-danger errorField" id="e_shippingErrorField" style="display: none"></small>
                        </div>
                        <div class="col-md-4" id="e_ship_charges">
                            <input type="text" name="e_shipping_charges" class="form-control" value="0"
                                placeholder="Shipping Charges" disabled>
                            <small class="text-danger e_errorField" id="e_shipping_chargesErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="e_category" id="e_main-category" required>
                                <option value="">Select Category</option>
                                @forelse($mainCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger e_errorField" id="e_categoryErrorField"
                                style="display: none"></small>
                        </div>

                        <div class="col-md-6">
                            <select class="form-control" name="e_sub_category" id="e_sub-category" required>
                                <option value="0">Select Sub Category</option>
                                @forelse($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger e_errorField" id="e_subCategoryErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="e_condition" required>
                                <option value="">Condition</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                            </select>
                            <small class="text-danger e_errorField" id="e_conditionErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="e_location" id="e_locations" required>
                                <option value="">Select Location</option>
                                @forelse($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <small class="text-danger e_errorField" id="e_locationErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Weight" name="e_weight"
                                value="" min="0">
                            <p class="lngthUnit">lbs</p>
                            <small class="text-danger e_errorField" id="e_weightErrorField" style="display: none"></small>
                        </div>
                        {{-- <div class="col-md-6">
                            <select name="e_weight_unit" id="e_weight_unit" class="form-control">
                                <option value="">Select Weight Unit</option>
                                <option value="LB">LB</option>
                            </select>
                            <small class="text-danger e_errorField" id="e_weightUnitErrorField"
                                style="display: none"></small>
                        </div> --}}

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Length" name="e_length"
                                min="0" value="">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger e_errorField" id="e_lengthErrorField" style="display: none"></small>
                        </div>

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Width" name="e_width"
                                min="0" value="">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger e_errorField" id="e_widthErrorField" style="display: none"></small>
                        </div>

                        <div class="col-md-6">
                            <input type="number" step="any" class="form-control" placeholder="Height" name="e_height"
                                min="0" value="">
                            <p class="lngthUnit">in</p>
                            <small class="text-danger e_errorField" id="e_heightErrorField" style="display: none"></small>
                        </div>
                        {{-- <div class="col-md-6">
                            <select name="e_dimensions_unit" id="e_dimensions_unit" class="form-control">
                                <option value="">Select Dimensions Unit</option>
                                <option value="IN">INCH</option>
                            </select>
                            <small class="text-danger e_errorField" id="e_dimensionsErrorField"
                                style="display: none"></small>
                        </div> --}}
                        <div class="col-md-12">
                            <textarea name="e_description" placeholder="Product Description" required
                                class="form-control" cols="30" rows="5"></textarea>
                            <small class="text-danger e_errorField" id="e_descriptionErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-6 offset-5 mb-4" id="e_product_file_area" style="display:none">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="e_product_file" id="e_product_file"
                                    accept=".zip">
                                <label class="custom-file-label" for="validatedCustomFile"><i
                                        class="fa fa-cloud-upload"></i>Select Product File</label>
                            </div>
                            <small class="text-danger errorField" id="eProductFileErrorField"
                                style="display: none"></small>
                        </div>
                        <div class="col-md-12 mb-4">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;height: 150px;">
                                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="" id="e_img_00"
                                                style="height: 150px;width: 200px;">
                                        </td>
                                        <td>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="e_product_picture"
                                                    id="e_product_picture_00" onchange="PreviewImageE(this,'00')"
                                                    accept=".png, .jpg, .jpeg" id="product_picture">
                                                <label class="custom-file-label" for="validatedCustomFile">Select
                                                    Pictures...</label>
                                            </div>
                                            <small class="text-danger e_errorField" id="e_productPictureErrorField"
                                                style="display: none"></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mb-4">
                            <h5 class="text-center">
                                Additional Images
                                <button type="button" class="orangeBtn" onclick="addMoreImgRowEdit()">+</button>
                            </h5>
                        </div>
                        <div class="col-md-12 mb-4">
                            <table>
                                <tbody id="addMoreImgDivEdit"></tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="orangeBtn updateOrangeBtn  w-100">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SPECIAL DEALS MODAL --}}
    <div class="modal fade" id="getFeaturedModal" tabindex="-1" role="dialog" aria-labelledby="getFeaturedModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header modal-top">
                    <h5 class="modal-title" id="getFeaturedModalLongTitle">Mark Product as Featured</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @forelse ($specialDealsPackages as $specialDealsPackage)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dealPackage"
                                id="{{ $specialDealsPackage->id }}_label" value="{{ $specialDealsPackage->id }}"
                                required>
                            <label class="form-check-label" for="{{ $specialDealsPackage->id }}_label">
                                {{ $specialDealsPackage->name }} Fee:
                                {{ '$' . $specialDealsPackage->amount }}
                            </label>
                            <input type="hidden" value="{{ $specialDealsPackage->amount }}"
                                id="fee{{ $specialDealsPackage->id }}" />
                        </div>
                    @empty

                    @endforelse
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" onclick="markProductAsSpecialDeal()" class="orangeBtn">Pay Now</a>
                </div>
            </div>
        </div>
    </div>
    {{-- END SPECIAL DEAL MODAL --}}


    <!-- Modal -->
    <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="getFeaturedModalLongTitle">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>
    <!-- Modal End -->


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
            </div>
        </div>
    </div>
    <!-- Modal End -->

    {{-- Add addresses for seller --}}



    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddress" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">

                    <form action="{{ url('seller/addSellerAddress') }}" method="post" class="row formStyle"
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
                            <input type="text" class="form-control" placeholder="Company Name" name="company_name"
                                required>
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
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">

                    <form action="{{ url('seller/updateSellerAddress') }}" method="post" class="row formStyle"
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
                            <input type="email" class="form-control" placeholder="Email Address" name="email"
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
                                id="company_name_e" required>
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
    {{-- end add addresses for seller --}}

    <!-- Add Tracking NO Modal -->
    <div class="modal fade" id="addTrackingNo" tabindex="-1" aria-labelledby="addTrackingNo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5" id="trackingArea">

                </div>
            </div>
        </div>
    @endsection
    @section('extra-js')
        <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD&disable-funding=credit,card"
                data-sdk-integration-source="button-factory"></script>
        <script>
            $(document).ready(function() {
                var platform_fees_perc = "{{ $setting->service_charges }}";

                $('#payment').on('hidden.bs.modal', function() {
                    $(".paypal-buttons").remove();
                });
                // $('#locations').select2();

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
                        url: "{{ url('seller/update_password') }}",
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
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                });

                $(document).ready(function() {

                    $("input[name='price']").on('change', function() {

                        let final_price = 0;
                        if ($(this).val() > 0) {
                            final_price = ($(this).val() / 100) * platform_fees_perc;
                            $('#final_price').val(parseFloat(final_price) + parseFloat($(this).val()));
                        }
                    });


                    $("input[name='discount_price']").on('change', function() {
                        if ($(this).val() <= 0) {
                            var final_price = ($("input[name='price']").val() / 100) *
                                platform_fees_perc;
                            $('#final_price').val(parseFloat(final_price) + parseFloat($(this).val()));
                        } else {
                            if ($(this).val() > 0) {
                                var final_price = ($(this).val() / 100) * platform_fees_perc;

                                $('#final_price').val(parseFloat(final_price) + parseFloat($(this)
                                    .val()));
                            }
                        }

                    });

                    $("input[name='e_price']").on('change', function() {

                        let final_price = 0;
                        if ($(this).val() > 0) {
                            final_price = ($(this).val() / 100) * platform_fees_perc;
                            $('#e_final_price').val(parseFloat(final_price) + parseFloat($(this)
                                .val()));
                        }
                    });


                    $("input[name='e_discount_price']").on('change', function() {
                        if ($(this).val() <= 0) {
                            var final_price = ($("input[name='e_price']").val() / 100) *
                                platform_fees_perc;
                            $('#e_final_price').val(parseFloat(final_price) + parseFloat($(this)
                                .val()));
                        } else {
                            if ($(this).val() > 0) {
                                var final_price = ($(this).val() / 100) * platform_fees_perc;

                                $('#e_final_price').val(parseFloat(final_price) + parseFloat($(this)
                                    .val()));
                            }
                        }

                    });

                });

                // $("input[name='e_free_ship_check']").change(function() {
                //     // var ischecked = ;
                //     if ($(this).is(':checked')) {
                //         $('input[name="e_shipping_charges"]').val(0);
                //         $('input[name="e_shipping_charges"]').attr('readonly', 'true');

                //     } else if (!$(this).is(":checked")) {
                //         $('input[name="e_shipping_charges"]').removeAttr('readonly');

                //     }
                // });


                // ADD PRODUCT
                $('#addProducts').submit(function(evt) {
                    evt.preventDefault();
                    var formData = new FormData();
                    formData.append('title', $('input[name=title]').val());
                    formData.append('product_type', $('select[name=product_type]').val());
                    formData.append('qty', $('input[name=qty]').val());
                    formData.append('price', $('input[name=price]').val());
                    formData.append('discount_price', $('input[name=discount_price]').val());
                    formData.append('vat', $('input[name=vat]').val());
                    formData.append('category', $('select[name=category]').val());
                    formData.append('brand', $('select[name=brand]').val());
                    formData.append('shipping', $('select[name=shipping]').val());
                    formData.append('shipping_charges', $('input[name=shipping_charges]').val());
                    formData.append('sub_category', $('select[name=sub_category]').val());
                    formData.append('condition', $('select[name=condition]').val());
                    formData.append('location', $('select[name=location]').val());
                    formData.append('weight', $('input[name=weight]').val());
                    // formData.append('weight_unit', $('select[name=weight_unit]').val());
                    formData.append('length', $('input[name=length]').val());
                    formData.append('width', $('input[name=width]').val());
                    formData.append('height', $('input[name=height]').val());
                    // formData.append('dimensions_unit', $('select[name=dimensions_unit]').val());
                    formData.append('description', $('textarea[name=description]').val());
                    formData.append('product_picture', $('input[name="product_picture"]')[0].files[0]);


                    if ($('input[name="product_file"]')[0].files[0] !== undefined) {
                        // console.log('khali NAHI');
                        formData.append('product_file', $('input[name="product_file"]')[0].files[0]);
                    } else {
                        // console.log('khali');
                        formData.append('product_file', null);
                    }

                    // Read selected files
                    var totalfiles = document.getElementsByClassName('addtional_picture').length;

                    for (var index = 0; index < totalfiles; index++) {
                        formData.append("additionalImages[]", document.getElementsByClassName(
                            'addtional_picture')[index].files[0]);
                    }

                    formData.append('_token', $('input[name=_token]').val());

                    $("#createProduct").attr('disabled', true);
                    $("#createProduct").find('i').show();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('seller/add-product') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            //Clearing all errors
                            // console.log(data);
                            // return;
                            $('.errorField').css('display', 'none');
                            if (!data.status) {
                                $(".adDanger").show();
                                $("#createProduct").attr('disabled', false);
                                $("#createProduct").find('i').hide();
                                //$(".addEvent").find('i').hide();
                                //$(".addEvent").attr('disabled', false);
                                if (Object.keys(data.errors).length > 0) {
                                    if (typeof data.errors.title != 'undefined') {
                                        $('#titleErrorField').text(data.errors.title[0]);
                                        $('#titleErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_type != 'undefined') {
                                        $('#productTypeErrorField').text(data.errors.product_type[
                                            0]);
                                        $('#productTypeErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.qty != 'undefined') {
                                        $('#qtyErrorField').text(data.errors.qty[
                                            0]);
                                        $('#qtyErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.category != 'undefined') {
                                        $('#categoryErrorField').text(data.errors.category[0]);
                                        $('#categoryErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.sub_category != 'undefined') {
                                        $('#subCategoryErrorField').text(data.errors.sub_category[
                                            0]);
                                        $('#subCategoryErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_type != 'undefined') {
                                        $('#productTypeErrorField').text(data.errors.product_type[
                                            0]);
                                        $('#productTypeErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.price != 'undefined') {
                                        $('#priceErrorField').text(data.errors.price[0]);
                                        $('#priceErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.brand != 'undefined') {
                                        $('#brandErrorField').text(data.errors.brand[0]);
                                        $('#brandErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.shipping != 'undefined') {
                                        $('#shippingErrorField').text(data.errors.shipping[0]);
                                        $('#shippingErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.shipping_charges != 'undefined') {
                                        $('#shipping_chargesErrorField').text(data.errors
                                            .shipping_charges[0]);
                                        $('#shipping_chargesErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.condition != 'undefined') {
                                        $('#conditionErrorField').text(data.errors.condition[0]);
                                        $('#conditionErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.location != 'undefined') {
                                        $('#locationErrorField').text(data.errors.location[0]);
                                        $('#locationErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.weight != 'undefined') {
                                        $('#weightErrorField').text(data.errors
                                            .weight[0]);
                                        $('#weightErrorField').css('display', 'block')
                                    }
                                    // if (typeof data.errors.weight_unit != 'undefined') {
                                    //     $('#weightUnitErrorField').text(data.errors
                                    //         .weight_unit[0]);
                                    //     $('#weightUnitErrorField').css('display', 'block')
                                    // }
                                    if (typeof data.errors.length != 'undefined') {
                                        $('#lengthErrorField').text(data.errors
                                            .length[0]);
                                        $('#lengthErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.width != 'undefined') {
                                        $('#widthErrorField').text(data.errors
                                            .width[0]);
                                        $('#widthErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.height != 'undefined') {
                                        $('#heightErrorField').text(data.errors
                                            .height[0]);
                                        $('#heightErrorField').css('display', 'block')
                                    }
                                    // if (typeof data.errors.dimensions_unit != 'undefined') {
                                    //     $('#dimensionsErrorField').text(data.errors
                                    //         .dimensions_unit[0]);
                                    //     $('#dimensionsErrorField').css('display', 'block')
                                    // }
                                    if (typeof data.errors.description != 'undefined') {
                                        $('#descriptionErrorField').text(data.description
                                            .description[0]);
                                        $('#descriptionErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_picture != 'undefined') {
                                        $('#productPictureErrorField').text(data.errors
                                            .product_picture[0]);
                                        $('#productPictureErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_file != 'undefined') {
                                        $('#productFileErrorField').text(data.errors
                                            .product_file[0]);
                                        $('#productFileErrorField').css('display', 'block')
                                    }
                                }
                            } else {
                                $(".adSuccess").show();
                                $("#createProduct").attr('disabled', true);
                                $("#createProduct").find('i').hide();
                                $(".adDanger").hide();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(data) {
                            $("#createProduct").attr('disabled', false);
                            $("#createProduct").find('i').hide();
                            console.log(data);
                        }
                    });
                });

                //Edit Product
                var base_url = "{{ url('/') }}";
                // $('.editProduct').on('click', function() {
                $(document).on('click', '.editProduct', function() {
                    let finalPrice = 0;
                    let fnfPrice = 0;
                    $('input[name=product_id]').val($(this).data('id'));
                    $.get("{{ url('seller/getProduct/') . '/' }}" + $(this).data('id'), function(d) {
                        //console.log(d);
                        $('input[name="e_title"]').val(d.product.product_name);
                        $('select[name="e_product_type"]').val(d.product.product_type);
                        $('input[name="e_qty"]').val(d.product.qty);

                        $('input[name="e_price"]').val(d.product.product_current_price);
                        $('input[name="e_discount_price"]').val(d.product.discount_price);

                        if (d.product.discount_price > 0) {
                            finalPrice = (d.product.discount_price / 100) * platform_fees_perc;
                            fnfPrice = parseFloat(finalPrice) + parseFloat(d.product.discount_price);

                            $('#e_final_price').val(fnfPrice.toFixed(2));
                        } else {
                            finalPrice = (d.product.product_current_price / 100) * platform_fees_perc;
                            fnfPrice = parseFloat(finalPrice) + parseFloat(d.product
                                .product_current_price);
                            $('#e_final_price').val(fnfPrice.toFixed(2));
                        }

                        // $('input[name="e_vat"]').val(d.product.vat);
                        $('select[name="e_category"]').val(d.product.category_id);
                        $('select[name="e_brand"]').val(d.product.manufacturer_id);
                        $('select[name="e_shipping"]').val(d.product.shipping);

                        if (d.product.sub_category_id == 0) {
                            $('select[name="e_sub_category"]').empty();
                            $('select[name="e_sub_category"]').append(
                                '<option value="0" selected>Select Sub Category</option>');
                        } else {
                            $('select[name="e_sub_category"]').append(
                                `<option value="${d.subCategory.id}" selected>${d.subCategory.name}</option>`
                            );
                        }

                        if (d.product.shipping == 3) {
                            $('input[name="e_shipping_charges"]').val(d.product.shipping_charges);
                            $('input[name="e_shipping_charges"]').attr('disabled', false);

                        } else {
                            $('input[name="e_shipping_charges"]').val(0);
                            $('input[name="e_shipping_charges"]').attr('disabled', true);
                        }

                        $('input[name="e_weight"]').val(d.product.weight);
                        $('select[name="e_weight_unit"]').val(d.product.weight_unit);
                        $('input[name="e_length"]').val(d.product.length);
                        $('input[name="e_width"]').val(d.product.width);
                        $('input[name="e_height"]').val(d.product.height);
                        // $('select[name="e_dimensions_unit"]').val(d.product.dimensions_unit);

                        $('select[name="e_condition"]').val(d.product.product_condition);
                        $('select[name="e_location"]').val(d.product.location_id);


                        $('textarea[name="e_description"]').html(d.product.description);

                        $("#e_img_00").attr("src", base_url + '/uploads/products/' + d.product
                            .product_image);

                        if (d.product.product_images.length > 0) {
                            var counterE = 0;
                            $('#addMoreImgDivEdit').html('');
                            for (let i = 0; i < d.product.product_images.length; ++i) {
                                counterE++;
                                $('#addMoreImgDivEdit').append(`
                                <tr id="e_imgRow_${counterE}">
                                    <td style="width: 50%;height: 150px;">
                                        <img src="${base_url}/uploads/products/${d.product.product_images[i].product_images}" alt="" id="img_${counterE}" style="height: 150px;width: 200px;">
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" name="saved_images[]" value="${d.product.product_images[i].id}">
                                            <input type="file" class="custom-file-input e_addtional_picture" name="addtional_picture[]" multiple id="product_picture_${counterE}" onchange="PreviewImage(this,${counterE})" accept=".png, .jpg, .jpeg"  id="product_picture" >
                                            <label class="custom-file-label" for="validatedCustomFile">Select Pictures...</label>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-md btn-danger" style="margin-bottom: 50px;margin-left: 10px;" onClick="removeImgRowEdit(${counterE})">X</button>
                                    </td>
                                </tr>`);
                            }
                        }
                        if (d.product.product_type == "Downloadable") {
                            $('#e_product_file_area').css('display', 'block');
                            var file = $('input[name="e_product_file"]').next('label').text(d.product
                                .product_file);
                        }
                    });
                    $('#editProducts').modal('show');
                });

                $('#editProducts').submit(function(evt) {
                    evt.preventDefault();
                    let id = $('input[name=product_id]').val();

                    var formData = new FormData();
                    formData.append('id', id);
                    formData.append('title', $('input[name=e_title]').val());
                    if ($('input[name="saved_images[]"]').val() !== undefined) {
                        formData.append('saved_images[]', $('input[name="saved_images[]"]').val());
                    } else {
                        formData.append('saved_images[]', null);

                    }
                    formData.append('product_type', $('select[name=e_product_type]').val());
                    formData.append('qty', $('input[name=e_qty]').val());
                    formData.append('price', $('input[name=e_price]').val());
                    formData.append('discount_price', $('input[name=e_discount_price]').val());
                    formData.append('vat', $('input[name=e_vat]').val());
                    formData.append('category', $('select[name=e_category]').val());
                    formData.append('brand', $('select[name=e_brand]').val());
                    formData.append('shipping', $('select[name=e_shipping]').val());
                    formData.append('shipping_charges', $('input[name=e_shipping_charges]').val());
                    formData.append('sub_category', $('select[name=e_sub_category]').val());
                    formData.append('condition', $('select[name=e_condition]').val());
                    formData.append('location', $('select[name=e_location]').val());
                    formData.append('weight', $('input[name=e_weight]').val());
                    formData.append('weight_unit', $('select[name=e_weight_unit]').val());
                    formData.append('length', $('input[name=e_length]').val());
                    formData.append('width', $('input[name=e_width]').val());
                    formData.append('height', $('input[name=e_height]').val());
                    formData.append('dimensions_unit', $('select[name=e_dimensions_unit]').val());
                    formData.append('description', $('textarea[name=e_description]').val());
                    formData.append('product_picture', $('input[name="e_product_picture"]')[0].files[0]);

                    if ($('input[name="e_product_file"]')[0].files[0] !== undefined) {
                        // console.log('khali NAHI');
                        formData.append('product_file', $('input[name="e_product_file"]')[0].files[0]);
                    } else {
                        // console.log('khali');
                        formData.append('product_file', null);
                    }


                    // Read selected files
                    var totalfiles = document.getElementsByClassName('e_addtional_picture').length;

                    for (var index = 0; index < totalfiles; index++) {
                        formData.append("additionalImages[]", document.getElementsByClassName(
                            'e_addtional_picture')[index].files[0]);
                    }

                    formData.append('_token', $('input[name=_token]').val());
                    $("#editProduct").attr('disabled', true);
                    $("#editProduct").find('i').show();
                    //$(this).attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('seller/edit-product') . '/' }}" + id,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            //Clearing all errors
                            // console.log(data);
                            // return;
                            $('.errorField').css('display', 'none');
                            if (!data.status) {
                                $(".adDanger").show();
                                $("#editProduct").attr('disabled', false);
                                $("#editProduct").find('i').show();
                                if (Object.keys(data.errors).length > 0) {
                                    if (typeof data.errors.title != 'undefined') {
                                        $('#titleErrorField').text(data.errors.title[0]);
                                        $('#titleErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.category != 'undefined') {
                                        $('#categoryErrorField').text(data.errors.category[0]);
                                        $('#categoryErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.sub_category != 'undefined') {
                                        $('#subCategoryErrorField').text(data.errors.sub_category[
                                            0]);
                                        $('#subCategoryErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_type != 'undefined') {
                                        $('#productTypeErrorField').text(data.errors.product_type[
                                            0]);
                                        $('#productTypeErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.qty != 'undefined') {
                                        $('#e_qtyErrorField').text(data.errors.qty[
                                            0]);
                                        $('#e_qtyErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.price != 'undefined') {
                                        $('#priceErrorField').text(data.errors.price[0]);
                                        $('#priceErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.brand != 'undefined') {
                                        $('#brandErrorField').text(data.errors.brand[0]);
                                        $('#brandErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.shipping != 'undefined') {
                                        $('#e_shippingErrorField').text(data.errors.shipping[0]);
                                        $('#e_shippingErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.shipping_charges != 'undefined') {
                                        $('#e_shipping_chargesErrorField').text(data.errors
                                            .shipping_charges[0]);
                                        $('#e_shipping_chargesErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.condition != 'undefined') {
                                        $('#conditionErrorField').text(data.errors.condition[0]);
                                        $('#conditionErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.location != 'undefined') {
                                        $('#locationErrorField').text(data.errors.location[0]);
                                        $('#locationErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.weight != 'undefined') {
                                        $('#e_weightErrorField').text(data.errors
                                            .weight[0]);
                                        $('#e_weightErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.weight_unit != 'undefined') {
                                        $('#e_weightUnitErrorField').text(data.errors
                                            .weight_unit[0]);
                                        $('#e_weightUnitErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.length != 'undefined') {
                                        $('#e_lengthErrorField').text(data.errors
                                            .length[0]);
                                        $('#e_lengthErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.width != 'undefined') {
                                        $('#e_widthErrorField').text(data.errors
                                            .width[0]);
                                        $('#e_widthErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.height != 'undefined') {
                                        $('#e_heightErrorField').text(data.errors
                                            .height[0]);
                                        $('#e_heightErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.dimensions_unit != 'undefined') {
                                        $('#e_dimensionsErrorField').text(data.errors
                                            .dimensions_unit[0]);
                                        $('#e_dimensionsErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.description != 'undefined') {
                                        $('#descriptionErrorField').text(data.description
                                            .description[0]);
                                        $('#descriptionErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_picture != 'undefined') {
                                        $('#productPictureErrorField').text(data.errors
                                            .product_picture[0]);
                                        $('#productPictureErrorField').css('display', 'block')
                                    }
                                    if (typeof data.errors.product_file != 'undefined') {
                                        $('#eProductFileErrorField').text(data.errors
                                            .product_file[0]);
                                        $('#eProductFileErrorField').css('display', 'block')
                                    }
                                }
                            } else {
                                $("#editProduct").attr('disabled', true);
                                $("#editProduct").find('i').hide();
                                $(".adSuccess").show();
                                $(".adDanger").hide();
                                // // Clear Data
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(data) {
                            $("#editProduct").attr('disabled', false);
                            $("#editProduct").find('i').hide();
                            console.log(data);
                        }
                    });
                });

                $('#main-category').on('change', function(e) {
                    var cat_id = e.target.value;
                    if (cat_id) {
                        $.ajax({
                            url: "{{ route('seller.getSubCategories') }}",
                            type: "Get",
                            data: {
                                cat_id: cat_id
                            },
                            success: function(data) {
                                $('#sub-category').empty();
                                $('#sub-category').removeAttr("style");
                                if (data.subcategories.length > 0) {
                                    // console.log(data.subcategories);

                                    $('#sub-category').append(
                                        '<option value="">Select Sub Category</option>');
                                    $.each(data.subcategories, function(index, subcategory) {

                                        $('#sub-category').append('<option value="' +
                                            subcategory
                                            .id + '">' + subcategory.name + '</option>');
                                    })
                                } else {
                                    $('#sub-category').append(
                                        '<option value="0">Select Sub Category</option>');
                                    $('#sub-category').attr("style", "pointer-events: none;");

                                }

                            }
                        })
                    }

                });

                $('#e_main-category').on('change', function(e) {
                    var cat_id = e.target.value;
                    $.ajax({
                        url: "{{ route('seller.getSubCategories') }}",
                        type: "Get",
                        data: {
                            cat_id: cat_id
                        },
                        success: function(data) {
                            $('#e_sub-category').empty();
                            $('#e_sub-category').removeAttr("style");
                            // $('#e_sub-category').append(
                            //     '<option value="">Select Sub Category</option>');
                            // $.each(data.subcategories, function(index, subcategory) {
                            //     $('#e_sub-category').append('<option value="' + subcategory
                            //         .id + '">' + subcategory.name + '</option>');
                            // })

                            if (data.subcategories.length > 0) {
                                // console.log(data.subcategories);

                                $('#e_sub-category').append(
                                    '<option value="">Select Sub Category</option>');
                                $.each(data.subcategories, function(index, subcategory) {

                                    $('#e_sub-category').append('<option value="' +
                                        subcategory
                                        .id + '">' + subcategory.name + '</option>');
                                })
                            } else {
                                $('#e_sub-category').append(
                                    '<option value="0">Select Sub Category</option>');
                                $('#e_sub-category').attr("style", "pointer-events: none;");
                            }
                        }
                    })
                });


                $('#searchSellerProducts').on('submit', function(e) {
                    e.preventDefault();
                    let keyword = $('#keyword').val();
                    $.ajax({
                        url: "{{ url('seller/searchSellerProduct') }}",
                        type: "Get",
                        data: {
                            keyword: keyword
                        },
                        success: function(data) {
                            $('#productArea').html('');
                            let html = '';
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    html += `<div class="col-md-6">
                                            <div class="proThumb vertiThumb">
                                                <a href="/product-details/${product.id}">
                                                    <img src="${get_image_path(product.product_image)}" alt="" style="width: 146px;height:165px">
                                                </a>
                                                <div class="content">
                                                    <h4>
                                                        <a href="/product-details/${product.id}">${product.product_name}</a>
                                                    </h4>
                                                    <h4>Category: ${product.category.name}
                                                    </h4>
                                                    <div class="opt">
                                                            <span class="" onclick="removeProduct(${product.id})" data-id="${product.id}">
                                                                <i class="fa fa-trash"></i>
                                                            </span>
                                                            <span class="edit editProduct" data-id="${product.id}">
                                                                <i class="fa fa-edit"></i>
                                                            </span>
                                                        </div>
                                                    <span>$ ${product.product_current_price}</span>
                                                    <small>${product.description.substring(0, 100)}</small>
                                                </div>
                                            </div>
                                        </div>`;
                                })

                                $('#productArea').html(html);
                            } else {
                                $('#productArea').append(`<h5>No Product Found!</h5>`);
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
                        url: "{{ url('seller/searchSellerOrders') }}",
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
                                                <p>${counter}</p>
                                            </td>
                                            <td>
                                                <p>${order.order_no}</p>
                                            </td>
                                            <td>
                                                <p>${order.order_items[0].product.product_name}</p>
                                            </td>
                                            <td>
                                                <p>${order.order_items[0].product.category.name}</p>
                                            </td>`

                                    if (order.buyer !== null) {
                                        html += `<td>
                                                            <p>
                                                                ${order.buyer.name}
                                                            </p>
                                                        </td>`
                                    } else {
                                        html += `<td>
                                                            <p>
                                                                ${order.buyer.name}
                                                            </p>
                                                        </td>`
                                    }


                                    html += `<td>
                                                    <p>$${order.total_amount}</p>
                                                </td>
                                                <td>
                                                    <p>${formatDate(order.created_at)}</p>
                                                </td>
                                                <td>
                                                    <p>${order.order_status.toUpperCase()}</p>
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
                //

            });


            function PreviewImage(input, counter) {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(input.files[0]);

                oFReader.onload = function(oFREvent) {
                    document.getElementById('img_' + counter).src = oFREvent.target.result;
                };
            }

            function PreviewImageE(input, counter) {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(input.files[0]);

                oFReader.onload = function(oFREvent) {
                    document.getElementById('e_img_' + counter).src = oFREvent.target.result;
                };
            }

            var counter = 0;

            function addMoreImgRow() {
                counter++;
                $('#addMoreImgDiv').append(`
                <tr id="imgRow_${counter}">
                    <td style="width: 50%;height: 150px;">
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="" id="img_${counter}" style="height: 150px;width: 200px;">
                    </td>
                    <td>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input addtional_picture" name="addtional_picture[]" multiple id="product_picture_${counter}" onchange="PreviewImage(this,${counter})" accept=".png, .jpg, .jpeg"  id="product_picture" required>
                            <label class="custom-file-label" for="validatedCustomFile">Select Pictures...</label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-md btn-danger" style="margin-bottom: 50px;margin-left: 10px;" onClick="removeImgRow(${counter})">X</button>
                    </td>
                </tr>
            `);
            }

            var counterE = 111;

            function addMoreImgRowEdit() {
                console.log(counterE);
                counterE++;
                $('#addMoreImgDivEdit').append(`
                <tr id="e_imgRow_${counterE}">
                    <td style="width: 50%;height: 150px;">
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="" id="img_${counterE}" style="height: 150px;width: 200px;">
                    </td>
                    <td>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input e_addtional_picture" name="addtional_picture[]" multiple id="product_picture_${counterE}" onchange="PreviewImage(this,${counterE})" accept=".png, .jpg, .jpeg"  id="product_picture" required>
                            <label class="custom-file-label" for="validatedCustomFile">Select Pictures...</label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-md btn-danger" style="margin-bottom: 50px;margin-left: 10px;" onClick="removeImgRowEdit(${counterE})">X</button>
                    </td>
                </tr>
            `);
            }

            function removeImgRow(index) {
                $(`#imgRow_${index}`).remove();
            }

            function removeImgRowEdit(index) {
                $(`#e_imgRow_${index}`).remove();
            }

            function removeProduct(id) {
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        url: "{{ url('seller/removeProduct') }}/" + id,
                        method: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            // console.log(res);
                            location.reload();
                        }
                    })
                }
            }

            function get_image_path(img) {
                var path = '/assets/images/placeholder_img.jpg';
                if (img != null) {
                    // echo 1;
                    path = '/uploads/products/' + img ? '/uploads/products/' + img : path;
                    return path;
                } else {
                    return path;
                }
            }

            //ARCHIVE ORDER
            function archiveOrder(id, archive) {
                if (confirm('do you want to Archive this Order?')) {
                    $.ajax({
                        url: "{{ url('seller/archiveOrder') }}/" + id,
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            archive: archive
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            // console.log(res);
                            if (res == 0) {
                                toastr.error('Order not exist!');
                            } else {
                                toastr.success('Order Archive Successfully');
                                location.reload();
                            }

                        }
                    })
                }
            }

            //CHange ORDER STATUS
            function changeOrderStatus(id, status) {
                if (confirm('do you want to Change Order Status?')) {

                    let id = id;
                    let val = status;

                    $.ajax({
                        type: "get",
                        url: "{{ url('seller/changeOrderStatus') }}/" + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            val: val
                        },
                        success: function(data) {
                            if (data == 0) {
                                toastr.error('Exception Here !');
                            } else {
                                toastr.success('Order Status Updated Successfully');
                                location.reload();
                            }
                        }
                    })
                }
            }

            //VIEW ORDER DETAILS
            function viewOrderDetails(id, status) {
                $.ajax({
                    type: "get",
                    url: "{{ url('seller/getOrderDetails') }}/" + id,
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


            function editOrderTrackingNo(id) {
                $.ajax({
                    type: "get",
                    url: "{{ url('seller/editOrderTrackingNo') }}/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (!data.status) {
                            toastr.error(data.msg);
                        } else {
                            $('#trackingArea').html(data.html);
                            // $('#orderDetails').html(html);
                            $('#addTrackingNo').modal('show');
                        }
                    },
                    error: function(err) {
                        alert(err.responseJSON.errors);
                    }
                })
            }
            //DELETE SELLER ORDER
            function sellerDeleteOrder(id, status) {
                if (confirm('Are you sure you want to delete this Order?')) {
                    $.ajax({
                        type: "get",
                        url: "{{ url('seller/sellerDeleteOrder') }}/" + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data == 0) {
                                toastr.error('Exception Here !');
                            } else {
                                toastr.success('Order Deleted Successfully');
                                location.reload();
                            }
                        }
                    })
                }
            }


            //FEATURED WORK START

            function markAsSpeical(product_id) {
                $('#getFeaturedModal').modal('show');
                $('#getFeaturedModal').attr('data-id', product_id);
            }


            function markProductAsSpecialDeal() {

                let deal_id = $('input[name="dealPackage"]:checked').val();
                let amt = $('#fee' + deal_id).val();
                let product_id = $('#getFeaturedModal').attr('data-id');

                if (deal_id !== undefined) {
                    $('#payment').modal('show');
                    initPayPalButton(amt, product_id, deal_id);
                    $('#getFeaturedModal').modal('hide');
                } else {
                    alert('Please Select Featured Package!');

                }
            }

            function initPayPalButton(amount, product_id, deal_id) {
                paypal.Buttons({
                    style: {
                        shape: 'rect',
                        color: 'gold',
                        layout: 'vertical',
                        label: 'paypal',
                    },

                    createOrder: function(data, actions) {
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
                        return actions.order.capture().then(function(details) {
                            $.ajax({
                                url: "{{ route('mark-product-special-deal') }}",
                                data: {
                                    product_id: product_id,
                                    deal_id: deal_id,
                                    amount: amount
                                },
                                method: 'POST',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    // console.log(res);
                                    if (res.success === true) {
                                        toastr.success(res.message);
                                        location.reload();
                                    } else {
                                        toastr.error(res.errorMsg);
                                    }
                                }
                            })
                        });
                    },

                    onError: function(err) {
                        // console.log(err);
                        toastr.error('Check your card');
                    }
                }).render('#paypal-button-container');
            }


            // MARK AS FEATURED BANNER

            $(document).ready(function() {
                var _URL = window.URL || window.webkitURL;

                $('input[name="banner"]').change(function(e) {
                    var file, img;
                    if ((file = this.files[0])) {
                        img = new Image();
                        img.onload = function() {
                            // alert(this.width + " " + this.height);
                            if (this.width !== 790) {
                                alert('Banner Width not Matched!');
                                $('input[name="banner"]').val('');

                            }
                            if (this.height !== 439) {
                                alert('Banner Height not Matched!');
                                $('input[name="banner"]').val('');
                                // return;
                            }
                            return;
                        };
                        img.onerror = function() {
                            alert("not a valid file: " + file.type);
                            $('input[name="banner"]').val('');
                            return;
                        };
                        img.src = _URL.createObjectURL(file);


                    }

                });
            });

            function markAsFeatured() {

                let feat_id = $('input[name="featured_id"]:checked').val();
                let amt = $('#amount_' + feat_id).val();
                if ($('input[name="banner"]').val() == undefined || $('input[name="banner"]').val() == '') {
                    alert('Please Select Banner');
                    return;
                }

                if (feat_id !== undefined) {
                    $('#payment').modal('show');
                    initPayPalBtn(amt, feat_id);
                } else {
                    alert('Please Select Featured Package!');

                }
            }

            function initPayPalBtn(amount, feat_id) {
                paypal.Buttons({
                    style: {
                        shape: 'rect',
                        color: 'gold',
                        layout: 'vertical',
                        label: 'paypal',
                    },

                    createOrder: function(data, actions) {
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
                        return actions.order.capture().then(function(details) {

                            var formData = new FormData();
                            formData.append('banner', $('input[name="banner"]')[0].files[
                                0]);
                            formData.append('amount', amount);
                            formData.append('feat_id', feat_id);

                            $.ajax({
                                url: "{{ route('mark-as-featured') }}",
                                data: formData,
                                method: 'POST',
                                cache: false,
                                contentType: false,
                                processData: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    // console.log(res);
                                    if (res.success === true) {
                                        toastr.success(res.message);
                                        location.reload();
                                    } else {
                                        toastr.error(res.errorMsg);
                                    }
                                }
                            })
                        });
                    },

                    onError: function(err) {
                        // console.log(err);
                        toastr.error('Check your card');
                    }
                }).render('#paypal-button-container');
            }

            //Seller Adresses


            //Edit Address
            $('.editAddress').on('click', function() {

                $id = $(this).data('id');

                $("#address_id").val($id);

                $.ajax({
                    url: "{{ url('seller/getAddresses') }}/" + $id,

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

                });
            });


            //remove address
            $('.removeAddress').on('click', function() {

                $id = $(this).data('id');
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        url: "{{ url('seller/deleteAddress') }}/" + $id,
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(res) {
                            window.location.href = "{{ url('seller/dashboard') }}";
                        }
                    });
                }
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
                    url: "{{ url('seller/getStates/countryId/') }}/" + country,
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
                    url: "{{ url('seller/getCities/stateId/') }}/" + state,
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
                    url: "{{ url('seller/getCities/stateId/') }}/" + state,
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
                    url: "{{ url('seller/getCities/stateId/') }}/" + state,
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

            $('document').ready(function() {

                $('input[name="price"]').on("keyup", function() {

                    if (parseInt($('input[name="price"]').val()) < parseInt($('input[name="discount_price"]')
                            .val())) {
                        alert('Discount Should be Less then Price!');
                        $('input[name="discount_price"]').val('');
                    }
                });

                $('input[name="discount_price"]').on("keyup", function() {

                    if (parseInt($('input[name="price"]').val()) < parseInt($('input[name="discount_price"]')
                            .val())) {
                        alert('Discount Should be Less then Price!');
                        $('input[name="discount_price"]').val('');
                    }
                });

                $('select[name=product_type]').on('change', function() {

                    if ($(this).val() == "Downloadable") {
                        $('#product_file_area').css('display', 'block');
                    } else {
                        $('#product_file_area').css('display', 'none');

                    }
                });

                $('input[name="product_file"]').change(function() {
                    var i = $(this).next('label').clone();
                    var file = $('input[name="product_file"]')[0].files[0].name;
                    $(this).next('label').text(file);
                });


                $('select[name=e_product_type]').on('change', function() {

                    if ($(this).val() == "Downloadable") {
                        $('#e_product_file_area').css('display', 'block');
                    } else {
                        $('#e_product_file_area').css('display', 'none');

                    }
                });

                $('input[name="e_product_file"]').change(function() {
                    var i = $(this).next('label').clone();
                    var file = $('input[name="e_product_file"]')[0].files[0].name;
                    $(this).next('label').text(file);
                });
            });

            $('select[name=shipping]').on('change', function() {

                if ($(this).val() == 3) {
                    $('input[name="shipping_charges"]').attr('disabled', false);
                } else {
                    $('input[name="shipping_charges"]').val(0);
                    $('input[name="shipping_charges"]').attr('disabled', true);
                }
            });

            $('select[name=e_shipping]').on('change', function() {

                if ($(this).val() == 3) {
                    $('input[name="e_shipping_charges"]').attr('disabled', false);
                } else {
                    $('input[name="e_shipping_charges"]').val(0);
                    $('input[name="e_shipping_charges"]').attr('disabled', true);
                }
            });

            $(document).on('click', '.cloneProduct', function() {
                if (confirm('Are you sure you want to clone this?')) {
                    $.get("{{ url('seller/cloneProduct/') . '/' }}" + $(this).data('id'), function(d) {
                        if (d.status) {
                            toastr.success(d.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(d.message);
                        }
                    });
                }
            });
        </script>
    @endsection
