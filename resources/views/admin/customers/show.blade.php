@extends('admin.layouts.app')
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
                            <li class="breadcrumb-item active">Buyer Detail</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Buyer Detail</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $content->name ?? '' }}</td>
                                            <th> Email</th>

                                            <td>{{ $content->user->email ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $content->phone_number ?? '' }}</td>

                                            <th>Zip Code</th>
                                            <td>{{ $content->zip_code ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Term & Condition</th>
                                            <td>{{ $content->term_condition ?? '' }}</td>
                                            <th> Joined at</th>
                                            <td>{{ date('d-m-Y', strtotime($content->user->created_at)) ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <strong>
                                                    Address Details
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>First Name</th>
                                            <td> {{ $content->buyerAddress->first_name ?? '' }} </td>
                                            <th>Last Name</th>
                                            <td> {{ $content->buyerAddress->last_name ?? '' }} </td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td> {{ $content->buyerAddress->email ?? '' }}</td>
                                            <th>Phone</th>
                                            <td> {{ $content->buyerAddress->phone_no ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Country</th>
                                            <td> {{ $content->buyerAddress->countryName->name ?? '' }}</td>
                                            <th>State</th>
                                            <td> {{ $content->buyerAddress->stateName->name ?? '' }}</td>
                                        </tr>

                                        <tr>
                                            <th>City</th>
                                            <td> {{ $content->buyerAddress->cityName->name ?? '' }}</td>
                                            <th>Zip Code</th>
                                            <td> {{ $content->buyerAddress->zip_code ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Company Name</th>
                                            <td colspan="3"> {{ $content->buyerAddress->company_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address </th>
                                            <td colspan="3"> {{ $content->buyerAddress->address1 ?? '' }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th>Address 2</th>
                                            <td colspan="3"> {{ $content->buyerAddress->address2 ?? '' }}</td>
                                        </tr> --}}
                                    </thead>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        {{-- <tr>
                                            <td class="text-center" colspan="7">
                                                <strong>
                                                    Orders
                                                </strong>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            {{-- <th>ID</th> --}}
                                            <th>S.No </th>
                                            <th>Order ID </th>
                                            {{-- <th>Product</th> --}}
                                            {{-- <th>Buyer </th> --}}
                                            <th>Total </th>
                                            <th>Status </th>
                                            <th>Order Date </th>
                                            <th>Action </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $index => $item)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $item->order_no }}</td>
                                                {{-- <td>{{ $item->orderItems[0]->product->product_name }}</td> --}}
                                                <td>{{ presentPrice($item->total_amount) }}</td>

                                                <td style="text-transform: uppercase;">
                                                    {{ $item->order_status }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ url('/admin') }}/order/{{ $item->id }}">
                                                        <i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @empty

                                        @endforelse
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
