@extends('admin.layouts.app')
@section('title', 'Add Special Deal')
@section('section')


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Special Deal Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Special Deal Form</li>
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
                                <h3 class="card-title">Special Deal Form</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ url('admin/store-update') . '/' . $dealProduct->id }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="row">
                                        <div class="col">
                                            <label for="exampleInputFile">Product</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <select name="product_id" id="product_id" required>
                                                        <option value="">Select Product</option>
                                                        @foreach ($products as $item)
                                                            <option value="{{ $item->id }}" @if ($dealProduct->product_id == $item->id) selected @endif>
                                                                {{ $item->product_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="exampleInputFile">Special Deal Package</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <select name="deal_id" id="deal_id" class="form-control" required>
                                                        <option value="">Select Package</option>
                                                        @foreach ($deals as $item)
                                                            <option value="{{ $item->id }}" @if ($dealProduct->special_deals_id == $item->id) selected @endif>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="exampleInputFile">Start Date</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="start_date" id="start_date"
                                                    required value="{{ $dealProduct->start_date }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="exampleInputFile">End Date</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="end_date" id="end_date"
                                                    required value="{{ $dealProduct->end_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{ route('featuredAds.index') }}" class="btn btn-warning btn-md">Cancel</a>
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
    <script>
        $(document).ready(function() {

            $("#product_id").select2();

            $("#end_date").change(function() {
                var startDate = document.getElementById("start_date").value;
                var endDate = document.getElementById("end_date").value;

                if ((Date.parse(startDate) >= Date.parse(endDate))) {
                    alert("End date should be greater than Start date");
                    document.getElementById("end_date").value = "";
                }
            });
        });


        // MARK AS FEATURED BANNER
    </script>
@endsection
