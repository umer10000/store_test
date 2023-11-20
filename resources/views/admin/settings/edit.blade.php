@extends('admin.layouts.app')
@section('title', 'Admin Setting')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Setting Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Setting</li>
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
                                <h3 class="card-title">Site Settings</h3>
                            </div>
                            <form class="category-form" method="post" action="{{ route('settings') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="name">Site Title</label>
                                                <input type="text" class="form-control" name="site_title" id="name"
                                                    value="{{ $content->site_title ?? '' }}" placeholder="site title"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Company Name</label>
                                                <input type="text" class="form-control" name="company_name" id="name"
                                                    value="{{ $content->company_name ?? '' }}" placeholder="company_name"
                                                    required>
                                            </div>
                                            {{-- <div class="form-group">
                                            <label for="name">Shipping Rate</label>
                                            <select name="shipping_rate" id="shipping_rate" class="form-control" >
                                                <option value="">Select Shipping Rate</option>
                                                @foreach ($shippingRates as $shippingRate)
                                                    <option value="{{$shippingRate->id}}" @if ($content->shipping_rate == $shippingRate->id) selected @endif>{{$shippingRate->rate}}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                            <div class="form-group">
                                                <label for="name">Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="{{ $content->email ?? '' }}" placeholder="email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Phone </label>
                                                <input type="number" class="form-control" name="phone_no_1" id="name"
                                                    value="{{ $content->phone_no_1 ?? '' }}" placeholder="Phone Number"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Split IT Charges(%)</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="splitit_percentage" name="splitit_percentage"
                                                        value="{{ $content->splitit_percentage }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Stripe Charges(%)</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="stripe_percentage" name="stripe_percentage"
                                                        value="{{ $content->stripe_percentage }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Paypal Charges(%)</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="paypal_percentage" name="paypal_percentage"
                                                        value="{{ $content->paypal_percentage }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                            <label for="name">Phone no 2 </label>
                                            <input type="number" class="form-control" name="phone_no_2" id="name"
                                                   value="{{$content->phone_no_2??''}}" placeholder="phone_no_2">
                                        </div> --}}
                                            {{-- <div class=form-group">
                                            <label for="name">Payment Options</label>
                                            <div class="input-group-btn">
                                                    <div class="file-btn mt-4">
                                                        <span style="font-weight: bold;margin-right: 10px;">Paypal</span><input type="checkbox" id="paypal" @if ($content->paypal_check == 'yes') checked @endif name="paypal_check" value="yes">
                                                    </div>
                                                    <div class="file-btn mt-4">
                                                        <span style="font-weight: bold;margin-right: 10px;">Stripe</span><input type="checkbox" id="stipe" @if ($content->stripe_check == 'yes') checked @endif name="stripe_check" value="yes">
                                                    </div>
                                            </div>
                                        </div> --}}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Address</label>
                                                <input type="text" class="form-control" name="address" id="address"
                                                    value="{{ $content->address ?? '' }}" placeholder="address" required>
                                            </div>
                                            {{-- <div class="form-group">
                                            <label for="name">Facebook</label>
                                            <input type="url" class="form-control" name="facebook" id="facebook"
                                                   value="{{$content->facebook??''}}" placeholder="facebook"
                                                   >
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Twitter</label>
                                                <input type="text" class="form-control" name="tweeter" id="tweeter"
                                                    value="{{$content->tweeter??''}}" placeholder="Tweeter"
                                                    >
                                            </div>

                                            <div class="form-group">
                                                <label for="name">LinkedIn</label>
                                                <input type="text" class="form-control" name="LinkedIn" id="LinkedIn"
                                                    value="{{$content->linkedIn??''}}" placeholder="LinkedIn"
                                                    >
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Instagram</label>
                                                <input type="text" class="form-control" name="instagram" id="instagram"
                                                    value="{{$content->instagram??''}}" placeholder="instagram"
                                                    >
                                            </div> --}}
                                            <div class="form-group">
                                                <label for="name">Service Charges</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="service_charges" name="service_charges"
                                                        value="{{ $content->service_charges }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Tax</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control" id="tax"
                                                        name="tax" value="{{ $content->tax }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Split IT Charges</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="splitit_charges" name="splitit_charges"
                                                        value="{{ $content->splitit_charges }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Stripe Charges</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="stripe_charges" name="stripe_charges"
                                                        value="{{ $content->stripe_charges }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Paypal Charges</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control"
                                                        id="paypal_charges" name="paypal_charges"
                                                        value="{{ $content->paypal_charges }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Logo</label>
                                                <div class="input-group-btn">
                                                    <div class="image-upload">
                                                        <img src="{{ asset(!empty($content->logo) && file_exists('uploads/settings/' . $content->logo) ? 'uploads/settings/' . $content->logo : 'admin/dist/img/placeholder.png') }}"
                                                            class="img-responsive" width="100px" height="100px">
                                                        <div class="file-btn mt-4">
                                                            <input type="file" id="logo" name="logo" accept=".jpg,.png">
                                                            <input type="text" id="logo" name="logo"
                                                                value="{{ !empty($content->logo) ? $content->logo : '' }}"
                                                                hidden="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer float-right">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('admin/custom_js/custom.js') }}"></script>
@endsection
