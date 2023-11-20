@extends('admin.layouts.app')
@section('title', (isset($content->id) ? 'Edit' : 'Add') . ' Seller')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Seller Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Seller Form</li>
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
                                <h3 class="card-title">Seller</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ url('admin/seller-update/' . $seller->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input class="form-control  @error('phone') is-invalid @enderror" name="seller_name"
                                            id="seller_name" value="{{ $seller->name }}" required>
                                        @if (Session::has('err'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ Session::get('err') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" id="phone" value="{{ $seller->phone_number ?? old('phone') }}"
                                            placeholder="Phone" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Zip Code</label>
                                        <input class="form-control" name="zip_code" id="zip_code" placeholder="Zip Code"
                                            value="{{ $seller->zip_code ?? old('zip') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="category">About</label>
                                        <textarea class="form-control" name="about" id="ablout" placeholder="About Seller"
                                            required>{{ $seller->about ?? old('about') }}</textarea>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{ url('admin/sellers') }}" class="btn btn-warning btn-md">Cancel</a>
                                </div>
                        </div>
                        <!-- /.card-body -->


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
        function PreviewImage(inputId, id) {

            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(inputId).files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById(id).src = oFREvent.target.result;
            };
        }
    </script>
@endsection
