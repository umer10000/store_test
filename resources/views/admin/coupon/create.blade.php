@extends('admin.layouts.app')
@section('title', 'Add Coupon')
@section('section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .help-block{
            color:red;
        }
        .has-error{
            border-block-color: red;
        }
        .select2-selection__choice{
            background-color: #343a40 !important;
        }

    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Coupon Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Coupon Form</li>
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
                    <div class="col-md-8">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Coupon</h3>
                            </div>
                            <form class="coupon-form" method="post" action="{{route('coupons.create')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                        <div class="form-group">
                                            <label for="code">Coupon Code</label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="code" value="{{old('code')}}" placeholder="Coupon Code">
                                            @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="value">Value</label>
                                            <input type="number" class="form-control @error('value') is-invalid @enderror" name="value" id="value" value="{{old('value')}}" placeholder="Value">
                                            @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                                                <option>Select</option>
                                                <option value="value" @if(old('type') == 'value') selected @endif>Value</option>
                                                <option value="percentage" @if(old('type') == 'percentage') selected @endif>Percentage</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="value">Customers</label>
                                            <select class="customers form-control" id="customers" name="customers[]" multiple="multiple">
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->user_id}}">{{$customer->first_name .' '.$customer->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="value">Expiration Date</label>
                                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="{{old('expiration_date')}}" placeholder="Expiration Date">
                                        </div>

                                        <div class="form-group">
                                            <label for="value">Usage (How much time coupon can be use)</label>
                                            <input type="number" class="form-control" name="usage" id="usage" value="{{old('usage')}}" placeholder="Usage">
                                        </div>

                                        <div class="form-group">
                                            <label for="" class="mr-4">Status</label>
                                            <label class="switch">
                                                <input type="checkbox" name="status" checked data-id="" id="status-switch">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('coupons.index')}}" class="btn btn-warning btn-md">Cancel</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.customers').select2();
        });
    </script>
@endsection
