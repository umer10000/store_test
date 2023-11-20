@extends('admin.layouts.app')
@section('title', $product->product_name ?? 'Product')
@section('page_css')
    <style>
        th {
            background-color: #f7f7f7;
        }

    </style>
@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6 offset-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Product Detail
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product Detail</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <td>{{ $product->product_name ?? '' }}</td>
                                            <th>Category</th>
                                            <td>{{ $product->category->name ?? '' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Sub-Category</th>
                                            <td>{{ $product->sub_category->name ?? '' }}</td>
                                            <th>Brand</th>
                                            <td>
                                                {{ $product->manufacturer->name ?? '' }}
                                            </td>

                                        </tr>
                                        <tr>

                                            <th>Product Type</th>
                                            <td>{!! $product->product_type ?? '' !!}</td>
                                            <th>Product Condition</th>
                                            <td>{{ $product->product_condition ?? '' }}</td>
                                        </tr>
                                        <tr>

                                            <th>Seller</th>
                                            <td>
                                                {{ $product->seller->name ?? '' }}
                                            </td>
                                            <th>Current Price</th>
                                            <td>{{ presentPrice($product->product_current_price ?? '') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>
                                                {{ $product->countryName->name ?? '' }}
                                            </td>
                                            <th>Discounted Price</th>
                                            <td>{{ presentPrice($product->discount_price ?? '') }}</td>

                                        </tr>
                                        <tr>
                                            {{-- <th>Shipping Charges</th>
                                            <td>{{ presentPrice($product->shipping_charges ?? '') }}</td> --}}
                                            <th>Status</th>
                                            <td colspan="3">
                                                @if ($product->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">In Active</span>
                                                @endif
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td colspan="3">{{ $product->description ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Image</th>
                                            <td colspan="3">
                                                <img src="{{ productImage(@$product->product_image) }}" width="100px"
                                                    height="100px">
                                                @foreach ($product->product_images as $product_image)
                                                    <img src="{{ productImage(@$product_image->product_images) }}"
                                                        width="100px" height="100px">
                                                @endforeach
                                            </td>
                                        </tr>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

    </div>
@endsection
