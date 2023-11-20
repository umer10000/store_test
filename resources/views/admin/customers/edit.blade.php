@extends('admin.layouts.app')
@section('title', (isset($content->id) ? 'Edit' : 'Add') . ' Buyer')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Buyer Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Buyer Form</li>
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
                                <h3 class="card-title">Buyer</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ url('admin/buyer-update/' . $buyer->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input class="form-control @if (Session::has('err')) is-invalid @endif" name="buyer_name"
                                            id="buyer_name" value="{{ $buyer->name }}" required>
                                        @if (Session::has('err'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ Session::get('err') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" id="phone" value="{{ $buyer->phone_number ?? old('phone') }}"
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
                                            value="{{ $buyer->zip_code ?? old('zip') }}" required />
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{ url('admin/buyers') }}" class="btn btn-warning btn-md">Cancel</a>
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
