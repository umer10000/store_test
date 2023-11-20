@extends('admin.layouts.app')
@section('title', 'Admin Shop')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shop Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Shop Form</li>
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
                    <div class="col-md-9">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Shop</h3>
                            </div>
                            <form class="category-form" method="post" action="{{ route('update-admin-shop') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Name" required @if (!empty($shop)) value="{{ $shop->name }}" @endif>
                                        </div>
                                        {{-- <div class="col">
                                            <label for="name">Email</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="Email" required @if (!empty($shop)) value="{{ $shop->user->email }}" @endif>
                                        </div> --}}
                                        <div class="col">
                                            <label for="name">Phone </label>
                                            <input type="number" class="form-control" name="phone_number"
                                                id="phone_number" placeholder="Phone Number" required
                                                @if (!empty($shop)) value="{{ $shop->phone_number }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Zip Code</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" id="zip_code"
                                                    placeholder="Zip Code" name="zip_code" required
                                                    @if (!empty($shop)) value="{{ $shop->zip_code }}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="name">About</label>
                                            <div class="input-group">
                                                @if (!empty($shop))
                                                    <textarea name="about" id="about" cols="" class="form-control"
                                                        rows="10" name="about">{{ $shop->about }}</textarea>
                                                @else
                                                    <textarea name="about" id="about" cols="" class="form-control"
                                                        rows="10" name="about"></textarea>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer float-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
