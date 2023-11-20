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
                            <form class="category-form" method="post" action="{{ route('update-admin-shop-address') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="first_name" id="first_name"
                                                placeholder="First Name" required @if (!empty($shop)) value="{{ $shop->first_name }}" @endif>
                                        </div>
                                        <div class="col">
                                            <label for="name">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" id="last_name"
                                                placeholder="Last Name" required @if (!empty($shop)) value="{{ $shop->last_name }}" @endif>
                                        </div>
                                        <div class="col">
                                            <label for="name">Email</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="Email" required @if (!empty($shop)) value="{{ $shop->email }}" @endif>
                                        </div>
                                        <div class="col">
                                            <label for="name">Phone</label>
                                            <input type="text" class="form-control" name="phone_no" id="phone_no"
                                                placeholder="Phone Number" required @if (!empty($shop)) value="{{ $shop->phone_no }}" @endif>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="name">Company Name</label>
                                            <input type="text" class="form-control" name="company_name" id="company_name"
                                                placeholder="Company Name" required @if (!empty($shop)) value="{{ $shop->company_name }}" @endif>
                                        </div>
                                        <div class="col">
                                            <label for="name">StreetLine</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="address1" name="address1"
                                                    required @if (!empty($shop)) value="{{ $shop->address1 }}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="name">Country</label>
                                            <div class="input-group">
                                                <select class="form-control country countries" name="country"
                                                    id="countryId1" required>
                                                    <option value="country">Select Country</option>
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->id }}" @if ($shop->country == $value->id) selected @endif>
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="name">State</label>
                                            <div class="input-group">
                                                <select name="state" id="stateId1" class="form-control state states"
                                                    required>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}" @if ($shop->state == $state->id) selected @endif>
                                                            {{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="name">City</label>
                                            <div class="input-group">
                                                <select class="form-control city" name="city" id="cityId1" required>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($shop->city == $city->id) selected @endif>
                                                            {{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="name">ZipCode</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="zip_code" name="zip_code"
                                                    required @if (!empty($shop)) value="{{ $shop->zip_code }}" @endif>
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
    <script>
        $(document).ready(function() {
            // get states by countries
            $('.country').on('change', function() {
                var country = $(this).val();

                $.ajax({
                    type: "get",
                    dataType: "JSON",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('admin/getStates/countryId/') }}/" + country,
                    data: {},
                    success: function(data) {
                        if (country != null) {
                            var html = "";
                            $.each(data.response_data, function(index, obj) {
                                html += "<option value='" + obj.id + "'>" + obj.name +
                                    "</option>"
                            })
                            $('.state').html(html);
                            $('.state').trigger('change');
                        }
                    },
                });

            });

            // get cities by states
            $('.state').on('change', function() {
                var state = $(this).val();

                $.ajax({
                    type: "get",
                    dataType: "JSON",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('admin/getCities/stateId/') }}/" + state,
                    data: {},

                    success: function(data) {
                        if (state != null) {
                            var html = "";
                            $.each(data.response_data, function(index, obj) {
                                html += "<option value='" + obj.id + "'>" + obj.name +
                                    "</option>"
                            })
                            $('.city').html(html);
                        }
                    },
                    error: function() {},
                    complete: function() {}
                });

            });
        });
    </script>
@endsection
