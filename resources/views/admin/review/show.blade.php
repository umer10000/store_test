@extends('admin.layouts.app')
@section('title', 'Product Reviews')
@section('page_css')
    <!-- Datatables -->
    <link href="{{ asset('admin/datatables/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
    <style>
        th{
            background-color: #f7f7f7;
        }
    </style>
@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6"> </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Review Detail</li>
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
                                <h3 class="card-title">Review Detail</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="">Product</th>
                                            <td colspan="" >{{$product_review->product->product_name ?? ''}}</td>
                                        </tr>
                                        {{--<tr>--}}
                                            {{--<th>Customer</th>--}}
                                            {{--<td colspan="4">{{$product_review->customer->first_name ??''}} {{$product_review->customer->last_name ??''}}</td>--}}
                                        {{--</tr>--}}
                                        <tr>
                                            <th>Author</th>
                                            <td colspan="">{{($product_review->author !== null) ? $product_review->author : $product_review->buyer->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td colspan="">{{$product_review->description ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Rating</th>
                                            <td>{{$product_review->rating ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($product_review->status == 1)
                                                    <span class="badge badge-success">Enabled</span>
                                                @else
                                                    <span class="badge badge-warning">Disabled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </thead>
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
