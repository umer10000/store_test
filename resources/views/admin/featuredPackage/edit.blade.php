@extends('admin.layouts.app')
@section('title', 'Add Featured Package')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Featured Ad Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Featured Package Form</li>
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
                                <h3 class="card-title">Featured Package Form</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ route('featuredPackage.update', ['id' => $package->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputFile">Title</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="title" id="title"
                                                value="{{ $package->name }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Amount</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="amount" id="amount" step="any"
                                                value="{{ $package->amount }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">No of Days</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="days" id="days"
                                                value="{{ $package->days }}" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{ route('featuredPackage.index') }}"
                                        class="btn btn-warning btn-md">Cancel</a>
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
@endsection
