@extends('admin.layouts.app')
@section('title', (isset($content->id) ? 'Edit' : 'Add') . ' Order')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Order Form</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Order</h3>
                            </div>

                            <div class="card-body">
                                @if (Session::has('msg'))
                                    <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                @endif
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <ul class="p-0 m-0" style="list-style: none;">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="category-form" method="post"
                                    action="{{ url('admin/order-update/' . $order->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Item</th>
                                                <th>Shipping</th>
                                                <th class="right">Unit Cost</th>
                                                <th class="center">Qty</th>
                                                <th class="right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $subTotal = 0;
                                                $isShippingLabel = 0;
                                            @endphp
                                            @forelse($order->orderItems as $orderItems)
                                                <tr>
                                                    <input type="hidden" class="form-control" name="order_item_id[]"
                                                        value="{{ $orderItems->id }}">
                                                    <td class="center">{{ $counter++ }}</td>
                                                    <td class="left strong">
                                                        {{ $orderItems->product->product_name }}
                                                    </td>
                                                    <td>
                                                        @if ($orderItems->shipping_name !== null || $orderItems->shipping_cost > 0)
                                                            @php $isShippingLabel++ @endphp
                                                            @forelse ($orderItems->shippingLabels as $index => $item)

                                                                <input type="hidden" value="{{ $item->id }}"
                                                                    name="label_id[]">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $item->tracking_number }}"
                                                                    placeholder="Tracking No"
                                                                    name="tracking_no[{{ $item->id }}][]" required>
                                                                <input type="file" class="form-control"
                                                                    name="shipping_doc_name[{{ $item->id }}][]"
                                                                    title="Upload Shipping Label">
                                                                <br>
                                                                (Tracking # : {{ $item->tracking_number }}) <br>
                                                                @if ($item->shipping_doc_name !== null)
                                                                    <a href="{{ asset('uploads/shipmentDocs/' . $item->shipping_doc_name) }}"
                                                                        target="_blank">Shipment Label</a><br>
                                                                @endif
                                                            @empty
                                                                @for ($i = 0; $i < $orderItems->product_qty; $i++)
                                                                    <input type="text" class="form-control" value=""
                                                                        name="tracking_no[{{ $orderItems->id }}][]"
                                                                        placeholder="Tracking No" required>
                                                                    <input type="file" class="form-control"
                                                                        name="shipping_doc_name[{{ $orderItems->id }}][]"
                                                                        title="Upload Shipping Label" required>
                                                                    <br>
                                                                @endfor
                                                            @endforelse
                                                        @endif
                                                    </td>
                                                    <td class="right">
                                                        {{ presentPrice($orderItems->product_per_price) }}
                                                    </td>
                                                    <td class="center">{{ $orderItems->product_qty }}</td>
                                                    <td class="right">
                                                        {{ presentPrice($orderItems->product_per_price * $orderItems->product_qty) }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $subTotal += $orderItems->product_per_price;
                                                @endphp
                                                @empty

                                                @endforelse
                                        </table>

                                        @if ($isShippingLabel > 0)
                                            <div class="row text-center">
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                    <form action="{{ url('admin/update-order-address') . '/' . $order->id }}"
                                        class="" method="post">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-12">
                                                <h3>Buyer Address</h3>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="">Name</label>
                                                    <input type="text" class="form-control" placeholder="Name"
                                                        name="buyer_name" required value="{{ $order->buyer_name }}">
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Last Name"
                                                        name="last_name" required>
                                                </div>
                                            </div> --}}
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="email" class="form-control" placeholder="Email Address"
                                                        name="email" required value="{{ $order->buyer_email }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="">Phone</label>
                                                    <div class="input-group">
                                                        <input type="tel" class="form-control phone_no" name="phone_no"
                                                            id="phone_no" placeholder="Phone Number" required
                                                            value="{{ $order->phone_no }}">
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">Country</label>
                                                    <input type="text" class="form-control" placeholder="Company Name"
                                                        name="company_name" value="{{ $order->phone_no }}">
                                                </div>
                                            </div> --}}
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">Address</label>
                                                    <textarea type="text" class="form-control" placeholder="Street Line"
                                                        name="address1" required>{{ $order->address1 }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Country</label>
                                                    <select class="form-control country countries" name="country" id="countryId"
                                                        required>
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $value)
                                                            <option value="{{ $value->id }}" @if ($order->country == $value->id) selected @endif>
                                                                {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">State</label>
                                                <select name="state" id="stateId" class="form-control states" required>
                                                    <option value="">Select State</option>
                                                    <option value="{{ $order->state }}" selected>
                                                        {{ $order->state_name->name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">City</label>
                                                <select class="form-control cities" name="city" id="cityId" required>
                                                    <option value="">Select City</option>
                                                    <option value="{{ $order->city }}" selected>
                                                        {{ $order->city_name->name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Zip Code</label>
                                                <input type="text" class="form-control" placeholder="Zip Code" name="zip_code"
                                                    required value="{{ $order->zip }}">
                                            </div>
                                            <div class="col-sm-12 text-right">
                                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="card-footer text-center">
                                    <a href="{{ url('admin/order') }}" class="btn btn-warning btn-md">Cancel</a>
                                </div> --}}
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
        </div>
        </section>
        </div>
    @endsection
    @section('script')
        <script src="{{ asset('front/js/location.js') }}"></script>
        <script>

        </script>
    @endsection
