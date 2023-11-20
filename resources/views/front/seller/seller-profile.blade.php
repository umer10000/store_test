@extends('front.layout.app')
@section('title', 'Seller Profile')

@section('content')
    <section class="proShowcase">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="media sellerPic sellerPicMain"
                                style="background: url({{ sellerCoverPicture(@$seller->cover_img) }}) center/cover no-repeat">
                                <img src="{{ sellerProfilePicture($seller->profile_picture) }}" class="mr-5"
                                    alt="" style="width: 300px;height:284px">
                                <div class="media-body">
                                    <h2>{{ $seller->name }}</h2>
                                    <p>{{ $seller->about }}</p>
                                    <!-- <a href="mailto:john@doe.com" class="info mb-3"><i class="fa fa-envelope"></i> john@doe.com</a> <br>
                                                                                    <a href="tel:+1234687970" class="info"><i class="fa fa-phone fa-flip-horizontal"></i> +1234687970</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="proFilter">
                                <h3>Filter Products</h3>
                                <div class="accordion" id="searchRadius">
                                    <div class="card location">
                                        <div class="card-header" id="category">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#categoryCollapse" aria-expanded="false"
                                                aria-controls="categoryCollapse">
                                                Category <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="categoryCollapse" class="collapse @if (Request::get('mainCategory')) {{ 'show' }} @endif"
                                            aria-labelledby="category" data-parent="#category">
                                            <div class="card-body">
                                                @php $mainCategoryArray = explode(',', Request::get('mainCategory')) @endphp
                                                @forelse($categories as $category)
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" name="mainCategory"
                                                            value="{{ $category->id }}"
                                                            class="form-check-input mainCategory"
                                                            id="{{ $category->name }}" @if (in_array($category->id, $mainCategoryArray)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="{{ $category->name }}">{{ $category->name }}</label>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card brand">
                                        <div class="card-header" id="brand">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#brandCollapse" aria-expanded="false"
                                                aria-controls="brandCollapse">
                                                Sub Category <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="brandCollapse" class="collapse @if (Request::get('subCategory')) {{ 'show' }} @endif"
                                            aria-labelledby="brand" data-parent="#brand">
                                            <div class="card-body">
                                                @php $CategoryArray = explode(',', Request::get('subCategory')) @endphp
                                                @forelse($subCategories as $subCategory)
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" class="form-check-input subCategory"
                                                            name="subCategory" value="{{ $subCategory->id }}"
                                                            id="{{ $subCategory->name }}" @if (in_array($subCategory->id, $CategoryArray)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="{{ $subCategory->name }}">{{ $subCategory->name }}</label>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card condition">
                                        <div class="card-header" id="condition">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#conditionCollapse" aria-expanded="false"
                                                aria-controls="conditionCollapse">
                                                Condition <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="conditionCollapse" class="collapse @if (Request::get('condition')) {{ 'show' }} @endif"
                                            aria-labelledby="condition" data-parent="#condition">
                                            <div class="card-body">
                                                <div class="form-group form-check">
                                                    <input type="radio" class="form-check-input condition" id="used"
                                                        name="condition" value="used"
                                                        {{ Request::get('condition') == 'used' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="used">Used
                                                        <span>({{ $usedProducts }})</span></label>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="radio" class="form-check-input condition" id="new"
                                                        value="new" name="condition"
                                                        {{ Request::get('condition') == 'new' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="new">New
                                                        <span>({{ $newProducts }})</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card location">
                                        <div class="card-header" id="location">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#locationCollapse" aria-expanded="false"
                                                aria-controls="locationCollapse">
                                                Location <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="locationCollapse" class="collapse  @if (Request::get('location')) {{ 'show' }} @endif"
                                            aria-labelledby="location" data-parent="#location">
                                            <div class="card-body">

                                                @php $locationArray = explode(',', Request::get('location')) @endphp

                                                <select name="location" id="locationSelect"
                                                    class="form-control locationSelect" multiple
                                                    style="width: 150px !important;">
                                                    {{-- <option value="">Select Locations</option> --}}
                                                    <option></option>
                                                    @foreach ($locations as $item)
                                                        <option value="{{ $item->id }}" @if (in_array($item->id, $locationArray)) selected
                                                    @endif>{{ $item->name }}</option>
                                                    @endforeach
                                                </select><br>
                                            </div>
                                            <div class="text-center">
                                                <button id="locaionFilterBtn" class="btn btn-sm btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card price">
                                        <div class="card-header" id="price">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#priceCollapse" aria-expanded="false"
                                                aria-controls="priceCollapse">
                                                Price <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="priceCollapse" class="collapse @if (Request::get('price')) {{ 'show' }} @endif"
                                            aria-labelledby="price" data-parent="#price">
                                            <div class="card-body">
                                                <div id="slider-range" data-min="10" data-max="10000"></div>
                                                @php
                                                    $price = explode('-', Request::get('price'));
                                                @endphp
                                                <div class="price-filters">
                                                    <input type="number" id="filter-min" name="start_price"
                                                        value="@if (Request::get('price')) {{ $price[0] }} @endif" placeholder=230>
                                                    <input type="number" id="filter-max" name="end_price"
                                                        value="@if (Request::get('price')) {{ $price[0] }} @endif" placeholder=1233>
                                                </div>
                                                <br>
                                                <button id="Filter" class="btn btn-sm btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-8">
                            <div class="row">
                                @forelse($products as $product)
                                    <div class="col-lg-3 col-md-6">
                                        <a href="{{ url('product-details') . '/' . $product->id }}"
                                            class="proThumb">
                                            <img src="{{ productImage(@$product->product_image) }}" alt=""
                                                style="height: 204px;width: 193px">
                                            <div class="content">
                                                <h4>{{ $product->product_name }}</h4>
                                                <span>
                                                    @if ($product->discount_price > 0)
                                                        @php
                                                            $final_price_pecnt_amnt = ($product->discount_price / 100) * $setting->service_charges;
                                                            $final_price = $product->discount_price + $final_price_pecnt_amnt;
                                                        @endphp
                                                        {{ presentPrice($final_price) }}
                                                    @else
                                                        @php
                                                            $final_price_pecnt_amnt = ($product->product_current_price / 100) * $setting->service_charges;
                                                            $final_price = $product->product_current_price + $final_price_pecnt_amnt;
                                                        @endphp
                                                        {{ presentPrice($final_price) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-lg-6 col-md-12">
                                        <h5>No Product Found!</h5>
                                    </div>
                                @endforelse
                                <div class="col-md-12">
                                    {{ $products->appends(request()->input())->links() }}
                                    {{-- <nav aria-label="Page navigation example" class="pagiStyle mt-5"> --}}
                                    {{-- <ul class="pagination"> --}}
                                    {{-- <li class="page-item"><a class="page-link" href="#">Previous</a></li> --}}
                                    {{-- <li class="page-item"><a class="page-link" href="#">1</a></li> --}}
                                    {{-- <li class="page-item"><a class="page-link" href="#">2</a></li> --}}
                                    {{-- <li class="page-item"><a class="page-link" href="#">3</a></li> --}}
                                    {{-- <li class="page-item"><a class="page-link" href="#">Next</a></li> --}}
                                    {{-- </ul> --}}
                                    {{-- </nav> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('extra-js')
    <script>
        $('.category,.subCategory,.condition').change(function() {
            updateFilters()
        });


        $('#Filter').on('click', function() {
            updateFilters()
        });

        $('#locaionFilterBtn').on('click', function() {
            updateFilters()
        });

        function updateFilters() {
            var mainCategory = '';
            var subCategory = '';
            var location = '';
            var price = [];

            $('.mainCategory:checked').each(function(index, ele) {
                mainCategory += $(ele).val() + ','
            });

            $('.subCategory:checked').each(function(index, ele) {
                subCategory += $(ele).val() + ','
            });



            locations = $('.locationSelect').select2("val");
            locations.forEach(function(locationn) {
                location += locationn + ',';
            });

            let condition = $('input[name=condition]:checked').val()
            let shipping = $('input[name=shipping]:checked').val()
            let start_price = $('input[name=start_price]').val()
            let end_price = $('input[name=end_price]').val()

            // var checked_price = $('.price_search:checked').val();
            // price = checked_price !== undefined ? checked_price.split(",") : [];

            var sort = $('select[name=sort_search]').val();

            var redirectUrl = "{{ url('seller/profile') . '/' . Request::segment(3) . '?' }}";
            var queryParams = ''

            if (mainCategory != '') {

                queryParams += 'mainCategory=' + mainCategory.slice(0, -1);
            }

            if (subCategory != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'subCategory=' + subCategory.slice(0, -1);
            }

            if (location != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'location=' + location.slice(0, -1);
            }

            if (condition) {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'condition=' + condition;
            }


            if (start_price && end_price) {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'price=' + start_price + '-' + end_price;
            }


            window.location = redirectUrl + queryParams
        }
        $(document).ready(function() {
            $('#locationSelect').select2({
                placeholder: "Select a Location"
            });
        });
    </script>
@endsection
