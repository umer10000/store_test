@extends('front.layout.app')

@section('title', 'Products')

@section('content')
    <style>
        .form-control-borderless {
            border: none;
        }

        .form-control-borderless:hover,
        .form-control-borderless:active,
        .form-control-borderless:focus {
            border: none;
            outline: none;
            box-shadow: none;
        }

        /* Begin: Pagination Style */

        .pagination {
            justify-content: center;
            margin: 0;
        }

        .pagination .page-link {
            min-width: 54px;
            height: 48px;
            border-radius: 4px;
            margin: 0 6px;
            background: #ffffff;
            border: 1px solid #e7e7e7;
            color: #1f1f4b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-link:hover,
        .pagination .page-link:focus,
        .pagination .active .page-link,
        .pagination .active .page-link:hover,
        .pagination .active .page-link:focus {
            background: #ff5500;
            color: #fff;
        }

        /* END: Pagination Style */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0;
            /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance: textfield;
            /* Firefox */
        }

        .search {
            text-align: center;
        }

        .search-input {
            /* margin-top: 3em; */
            width: 100%;
            font-size: 18px;
            border: none;
            background: #eee;
            border-radius: 4px;
            padding: 0.5em 1em 0.5em 2.5em;
            margin-bottom: 10px;
        }

    </style>

    <div class="mainBan bgchnge" @if ($categoryImg !== null) style="background:
        url('{{ categoryImage($categoryImg) }}') top left/cover fixed no-repeat !important;"
    @else style="background: url('{{ $banner->banner_img ?? '' }}') top left/cover fixed no-repeat !important;"
        @endif>
        @if (Request::get('sort') || Request::get('brand') || Request::get('category') || Request::get('condition') || Request::get('location') || Request::get('price') || Request::get('shipping') || Request::get('mainCategory') || Request::get('query'))
            <h2 class=""> {{ $categoryName }}</h2>
        @else
            <h2 class="">All Products</h2>





        @endif

        <div class="cell">
            @auth
                <a href="{{ url('all-products') }}" class="orangeBtn">Buy Gear</a>
            @else
                <a href="{{ url('register') }}" class="orangeBtn">Buy Gear</a>
            @endauth
            <!-- <a href="#">Online & In Store</a> -->
        </div>
        <div class="cell">
            @auth
                <a href="{{ url('all-products') }}" class="blueBtn">Sell Gear</a>
            @else
                <a href="{{ url('register') }}" class="blueBtn">Sell Gear</a>
            @endauth
            <!-- <a href="#">In Store</a> -->
        </div>
    </div>
    <!-- END: Main Slider -->

    <section class="proShowcase relatedChnge relatedProSec">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="row">
                        <div class="col-lg-12">
                            {{-- <h2 class="title"> --}}
                            {{-- @if (Request::has('category')) --}}
                            {{-- @if ($parent_category !== null) --}}
                            {{-- {{$parent_category->parent_category[0]->name}} --}}
                            {{-- @endif --}}

                            {{-- @endif --}}
                            {{-- </h2> --}}
                            <ul class="breadcrumb">
                                <li>
                                    <a href="javascript:void(0)">
                                        @if (Request::get('sort') || Request::get('brand') || Request::get('category') || Request::get('condition') || Request::get('location') || Request::get('price') || Request::get('shipping') || Request::get('mainCategory'))
                                            {{ $categoryName }}
                                        @else
                                            All Products
                                        @endif
                                    </a>
                                </li>

                                {{-- @if (Request::has('category')) --}}
                                {{-- <li><a href="javascript:void(0)">@if ($parent_category !== null) {{$parent_category->parent_category[0]->name}} @endif</a></li> --}}
                                {{-- <li><a href="javascript:void(0)">{{Request::get('category')}}</a></li> --}}
                                {{-- @endif --}}
                            </ul>
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
                                                Category
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="categoryCollapse" class="collapse show" aria-labelledby="category"
                                            data-parent="#category">
                                            <div class="card-body">
                                                @php $categoryArray = explode(',', Request::get('category')) @endphp
                                                @forelse($categories as $category)
                                                    <div class="cst-has-menu">
                                                        <h4>{{ $category->name }}</h4>

                                                        <div class="cst-submenu" @if (in_array(Request::get('category'), $category->subCategories->pluck('id')->toArray())) style="display: block" @endif>
                                                            @forelse($category->subCategories as $subCategory)
                                                                <div class="form-group form-check">
                                                                    <input type="checkbox" name="category"
                                                                        class="form-check-input category"
                                                                        id="{{ $subCategory->name }}"
                                                                        value="{{ $subCategory->id }}"
                                                                        @if (in_array($subCategory->id, $categoryArray)) checked @endif>
                                                                    <label class="form-check-label"
                                                                        for="{{ $subCategory->name }}">{{ $subCategory->name }}
                                                                    </label>
                                                                </div>
                                                            @empty
                                                            @endforelse
                                                        </div>
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
                                                Brands <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div id="brandCollapse" class="collapse @if (Request::get('brand')) {{ 'show' }} @endif"
                                            aria-labelledby="brand" data-parent="#brand">
                                            <div class="card-body">
                                                @php $brandArray = explode(',', Request::get('brand')) @endphp
                                                @forelse($manufacturers as $manufacturer)
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" name="brand" class="form-check-input brand"
                                                            id="{{ $manufacturer->name }}"
                                                            value="{{ $manufacturer->id }}" @if (in_array($manufacturer->id, $brandArray)) checked @endif>
                                                        <label class="form-check-label"
                                                            for="{{ $manufacturer->name }}">{{ $manufacturer->name }}</label>
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
                                                    <input type="radio" name="condition" class="form-check-input condition"
                                                        id="used" value="used"
                                                        {{ Request::get('condition') == 'used' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="used">Used
                                                        <span>({{ $usedProducts }})</span></label>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="radio" name="condition" class="form-check-input condition"
                                                        id="new" value="new"
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

                                        <div id="locationCollapse"
                                            class="collapse multiSelect2Main @if (Request::get('location')) {{ 'show' }} @endif"
                                            aria-labelledby="location" data-parent="#location">
                                            <div class="card-body">
                                                {{-- <input class="search-input form-control" type="text"
                                                        placeholder="Search"> --}}
                                                @php $locationArray = explode(',', Request::get('location')) @endphp

                                                <select name="location" id="locationSelect"
                                                    class="form-control locationSelect" multiple
                                                    style="width: 150px !important;">
                                                    {{-- <option value="">Select Locations</option> --}}
                                                    <option></option>
                                                    @foreach ($countries as $item)
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
                                            <div class="card-body text-center">
                                                <div id="slider-range" data-min="10" data-max="10000">
                                                </div>
                                                <div class="price-filters">
                                                    @php
                                                        $price = explode('-', Request::get('price'));
                                                    @endphp
                                                    <input type="number" class="form-control" name="start_price"
                                                        id="filter-min" placeholder=1 @if (Request::get('price')) value="{{ $price[0] }}" @endif>
                                                    <input type="number" name="end_price" class="form-control"
                                                        id="filter-max" placeholder=100 @if (Request::get('price')) value="{{ $price[1] }}" @endif>
                                                </div><br>
                                                <button id="Filter" class="btn btn-sm btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shipping">
                                        <div class="card-header" id="shipping">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#shippingCollapse" aria-expanded="false"
                                                aria-controls="shippingCollapse">
                                                Shipping <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div id="shippingCollapse" class="collapse @if (Request::get('shipping')) {{ 'show' }} @endif"
                                            aria-labelledby="shipping" data-parent="#shipping">
                                            <div class="card-body">
                                                <div class="form-group form-check">
                                                    <input type="radio" name="shipping" class="form-check-input shipping"
                                                        id="AnyAmount" value="any_amount"
                                                        {{ Request::get('shipping') == 'any_amount' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="AnyAmount">Any Amount</label>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="radio" name="shipping" class="form-check-input shipping"
                                                        id="FreeShipping" value="free_shipping"
                                                        {{ Request::get('shipping') == 'free_shipping' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="FreeShipping">Free Shipping</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-8">
                            <div class="row">
                                <form
                                    class="col-lg-12 col-md-12 formStyle sortFilter d-flex justify-content-between align-items-center mb-4">
                                    <p class="m-0">{{ $products->currentPage() }} -
                                        {{ $products->lastPage() }} of
                                        {{ $products->total() }} results</p>
                                    <label for="" class="m-0">Sort By
                                        <select name="sort_search" class="form-control d-inline w-auto bg-white ml-2 mb-0">
                                            <option value="high_low"
                                                {{ Request::get('sort') == 'high_low' ? 'selected' : '' }}>Price: High to
                                                Low</option>
                                            <option value="low_high"
                                                {{ Request::get('sort') == 'low_high' ? 'selected' : '' }}>Price: Low to
                                                High</option>
                                            <option value="new_old"
                                                {{ Request::get('sort') == 'new_old' ? 'selected' : '' }}>Newest to
                                                Oldest</option>
                                            <option value="old_new"
                                                {{ Request::get('sort') == 'old_new' ? 'selected' : '' }}>Oldest to
                                                Newest</option>
                                        </select>
                                    </label>
                                </form>
                                @php
                                    $d_final_price_pecnt_amnt = 0;
                                    $d_final_price = 0;
                                    $final_price_pecnt_amnt = 0;
                                    $final_price = 0;
                                @endphp
                                @forelse($products as $product)
                                    <div class="col-lg-3 col-md-6">
                                        <a href="{{ route('product-details.show', [$product->id]) }}"
                                            class="proThumb">
                                            <img src="{{ productImage(@$product->product_image) }}"
                                                alt="{{ $product->product_name }}">
                                            <div class="content">
                                                <h4>{{ $product->product_name }}</h4>
                                                <span>
                                                    @php
                                                        $d_final_price_pecnt_amnt = ($product->discount_price / 100) * $setting->service_charges;
                                                        $d_final_price = $product->discount_price + $d_final_price_pecnt_amnt;
                                                        
                                                        $final_price_pecnt_amnt = ($product->product_current_price / 100) * $setting->service_charges;
                                                        $final_price = $product->product_current_price + $final_price_pecnt_amnt;
                                                    @endphp
                                                    @if ($product->discount_price > 0)
                                                        <del>
                                                            ${{ sprintf('%.2f', $final_price) }}
                                                        </del>

                                                        ${{ sprintf('%.2f', $d_final_price) }}
                                                    @else

                                                        ${{ sprintf('%.2f', $final_price) }}
                                                    @endif
                                                </span>
                                                @if ($product->shipping == 2)
                                                    <div class="freeShippingTag">
                                                        <span>Free Shipping</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-lg-12 col-md-12">
                                        <h4>NO ITEM FOUND!</h4>
                                    </div>
                                @endforelse
                                <div class="col-md-12" id="paginationNav">
                                    {{ $products->appends(request()->input())->links() }}
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
        $('.category, .brand, .condition,.shipping, select[name=sort_search]').change(function() {
            updateFilters()
        });


        $('#locaionFilterBtn').on('click', function() {
            updateFilters()
        });

        $('#Filter').on('click', function() {
            updateFilters()
        });

        function updateFilters() {
            var category = '';
            var brand = '';

            var locations = '';
            var location = '';
            var price = [];

            $('.category:checked').each(function(index, ele) {
                category += $(ele).val() + ','
            });

            $('.brand:checked').each(function(index, ele) {
                brand += $(ele).val() + ','
            });

            locations = $('.locationSelect').select2("val");
            locations.forEach(function(locationn) {
                location += locationn + ',';
            });
            let condition = $('input[name=condition]:checked').val()
            let shipping = $('input[name=shipping]:checked').val()
            let start_price = $('input[name=start_price]').val()
            let end_price = $('input[name=end_price]').val()
            let query = $('input[name=query]').val()

            // var checked_price = $('.price_search:checked').val();
            // price = checked_price !== undefined ? checked_price.split(",") : [];

            var sort = $('select[name=sort_search]').val()

            var redirectUrl = "{{ url('all-products?') }}";
            var queryParams = ''

            if (query != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'query=' + query;
            }
            if (category != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'category=' + category.slice(0, -1);
            }
            if (brand != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'brand=' + brand.slice(0, -1);
            }
            if (location != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'location=' + location.slice(0, -1);
            }
            if (sort != '') {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'sort=' + sort;
            }

            if (condition) {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'condition=' + condition;
            }

            if (shipping) {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'shipping=' + shipping;
            }

            if (start_price && end_price) {
                queryParams += queryParams == '' ? '' : '&';
                queryParams += 'price=' + start_price + '-' + end_price;
            }
            window.location = redirectUrl + queryParams
        }

        $('document').ready(function() {
            // $('#paginationNav nav').addClass('pagiStyle mt-5');
            $('#paginationNav nav ul').append(`
                                    <li class="page-item itemChnge">
                                            Go to Page
                                            <input type="number" class="jump_page" value="{{ isset($_REQUEST['page']) ? $_REQUEST['page'] : 1 }}">
                                            <a class=" go-link" href="javascript:void(0)">Go
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                    </li>
                                    `);
        })

        $(document).on('click', '.go-link', function(e) {
            let page = $('.jump_page').val();
            page = parseInt(page) || 1;
            setTimeout(function() {
                $(".page-link:contains(" + page + ")")[0].click();
            }, 1000);
        });


        // // get input field and add 'keyup' event listener
        // let searchInput = document.querySelector('.search-input');
        // searchInput.addEventListener('keyup', search);
        // // get all title
        // let titles = document.querySelectorAll('.main .form-check');
        // let searchTerm = '';
        // let tit = '';

        // function search(e) {
        //     // get input fieled value and change it to lower case
        //     searchTerm = e.target.value.toLowerCase();

        //     titles.forEach((title) => {
        //         // navigate to p in the title, get its value and change it to lower case
        //         tit = title.className.toLowerCase();
        //         // it search term not in the title's title hide the title. otherwise, show it.
        //         tit.includes(searchTerm) ? title.style.display = 'block' : title.style.display = 'none';
        //     });
        // }

        $(document).ready(function() {
            $('#locationSelect').select2({
                placeholder: "Select a Location"
            });
        });
    </script>
@endsection
