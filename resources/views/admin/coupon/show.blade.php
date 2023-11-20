@extends('admin.layouts.app')
@section('title', ($coupon->code ?? '') . ' Coupon')
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

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Coupon</li>
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
                                <h3 class="card-title">Coupon Detail</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Coupon Code</th>
                                            <td colspan="">{{$coupon->code ?? ''}}</td>
                                            <th>Value</th>
                                            <td colspan="">{{$coupon->value ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{$coupon->type ?? ''}}</td>
                                            <th>Expiration Date</th>
                                            <td>{{$coupon->expiration_date ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Usage</th>
                                            <td>{{$coupon->usage ?? ''}}</td>
                                            <th>Used</th>
                                            <td>{{$coupon->used ?? ''}}</td>

                                        </tr>
                                        <tr>

                                            <th>Status</th>
                                            <td>
                                                @if($coupon->status == 1)
                                                    <span class="badge badge-success">Enabled</span>
                                                @else
                                                    <span class="badge badge-warning">Disabled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @if(isset($coupon->customer_id) && $coupon->customer_id != null)
                                        <tr>
                                            <th>Customers</th>
                                            <td>{{$coupon->customer_id ?? ''}}</td>
                                        </tr>
                                    @endif
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
