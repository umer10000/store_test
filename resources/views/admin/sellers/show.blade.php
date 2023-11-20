@extends('admin.layouts.app')
@section('page_css')
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

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
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

</style>
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
                        <li class="breadcrumb-item active">Seller Detail</li>
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
                            <h3 class="card-title">Seller Detail</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="align-middle">Profile Picture</th>
                                        <td>
                                            <img src="{{ sellerProfilePicture(@$content->profile_picture) }}"
                                                width="100px" height="100px">
                                        </td>
                                        <th class="align-middle">Cover Picture</th>
                                        <td>
                                            <img src="{{ sellerCoverPicture(@$content->cover_img) }}" width="100px"
                                                height="100px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Name</th>
                                        <td>{{ $content->name ?? '' }}</td>
                                        <th> Email</th>
                                        <td>{{ $content->user->email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $content->phone_number ?? '' }}</td>
                                        <th>Zip Code</th>
                                        <td>{{ $content->zip_code ?? '' }}</td>
                                    </tr>
                                    <tr>

                                    </tr>
                                    <tr>
                                        <th>About</th>
                                        <td colspan="4">{{ $content->about ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;"> Joined at</th>
                                        <td colspan="4">
                                            {{ date('d-m-Y', strtotime($content->user->created_at)) ?? '' }}</td>
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
            <!-- tabs  -->
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                        aria-controls="pills-home" aria-selected="true">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Orders</a>
                </li>
                {{-- <li class="nav-item">
              <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</a>
            </li> --}}
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    {{-- products start --}}
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">

                                    <!-- /.card -->
                                    <div class="card">
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        {{-- <th>ID</th> --}}
                                                        <th>Product</th>
                                                        <th>Category</th>
                                                        <th>Price</th>
                                                        <th>Discount Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($content->products as $key => $value)
                                                        {{-- <td> {{ $value->user->id }} </td> --}}
                                                        <tr>
                                                            {{-- <td> {{ $value->id }} </td> --}}
                                                            <td> {{ $value->product_name }} </td>
                                                            <td>{{ $value->category->name }} </td>
                                                            <td> {{ presentPrice($value->product_current_price) }}
                                                            </td>
                                                            <td> {{ presentPrice($value->discount_price) }} </td>

                                                            <td align="center"> <a
                                                                    href="{{ route('product.show', [$value->id]) }}">
                                                                    <i class="fas fa-eye"></i></a> </td>
                                                        </tr>
                                                    @endforeach
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
                    {{-- products end --}}
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="container">

                        {{-- Orders start --}}
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">

                                        <!-- /.card -->
                                        <div class="card">
                                            <div class="card-body">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>ID</th> --}}
                                                            <th>Order No </th>
                                                            <th>Buyer </th>
                                                            <th>Total </th>
                                                            <th>Status </th>
                                                            <th>Order Date </th>
                                                            <th>Action </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($orders as $item)
                                                            <tr>
                                                                <td>{{ $item->order_no }}</td>
                                                                <td>
                                                                    @if ($item->buyer_id !== null)
                                                                        {{ $item->buyer->name }}
                                                                        @else
                                                                        {{ $item->buyer_name }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ presentPrice($item->total_amount) }}</td>
                                                                <td style="text-transform: uppercase;">
                                                                    {{ $item->order_status }}</td>
                                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <a
                                                                        href="{{ url('/admin') }}/order/{{ $item->id }}">
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
                        {{-- Orders end --}}

                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div> --}}
            </div>

        </div>
        <!-- /tabs  -->
</div>
<!-- /.container-fluid -->
</section>

</div>
@endsection
