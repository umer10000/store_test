@extends('front.layout.app')

@section('title', $product->product_name)


@section('content')
    <style>
        .btn_choose_sent_check_b {
            background: #EF2D56;
            color: #fff;
            box-shadow: 0 10px 20px rgba(125, 147, 178, .3);
            border: none;
            border-radius: 3px;
            font-size: 16px;
            line-height: 10px;
            padding: 16px 20px 16px 46px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            margin-right: 30px;
            transition: all .3s;
            height: auto;
            cursor: pointer;
            position: relative;
            outline: none;
        }

        .descBox {
            padding: 4% 0;
            background-color: #ededee;
        }

        .userMain {
            margin: 3em 0 0.5em;
        }

        .userReview {
            display: flex;
            margin-bottom: 30px;
        }

        .reviewContent {
            padding-left: 15px;
        }

        .reviewForm {
            padding: 30px 0 0;
        }

        .star {
            color: #f7941d !important;
        }

        .gray {
            color: #676767 !important
        }

        .reviewForm>div>span>i.hover {
            color: rgb(255, 192, 87) !important;
        }

        .userReview img {
            height: 70px;
            width: 70px;
            border-radius: 50px;
        }

        .reviewContent h5 {
            color: #747474;
            margin-bottom: 8px;
            font-size: 0.9333em;
            font-weight: 600;
        }

        .reviewContent h5 {
            color: #747474;
            margin-bottom: 8px;
            font-size: 0.9333em;
            font-weight: 600;
        }

        .userMain h2 {
            font-size: 1.6em;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #372644;
        }

        .reviewContent i {
            font-size: 14px;
        }

    </style>

    <section class="proDetail">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="slider-for">
                        <div class="zooM-image">
                            <img src="{{ productImage(@$product->product_image) }}" alt="" class="w-100 magniflier">
                        </div>
                        @forelse($product->product_images as $key => $product_images)
                            <div>
                                <img src="{{ productImage(@$product_images->product_images) }}" alt=""
                                    class="w-100 magniflier">
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="slider-nav">
                        <div>
                            <img src="{{ productImage(@$product->product_image) }}" alt="" class="w-100">
                        </div>
                        @forelse($product->product_images as $product_images)
                            <div>
                                <img src="{{ productImage(@$product_images->product_images) }}" alt=""
                                    class="w-100">
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>{{ $product->product_name }}</h3>
                    <p class="tags">
                        <a href="javascript:void(0)">{{ $product->category->name }}</a>
                        @isset($product->sub_category)
                            ,<a href="javascript:void(0)">{{ $product->sub_category->name }}</a>
                        @endisset
                    </p>
                    <!-- 1. added class remove_to_wishlist to add to favorites italic tag -->

                    <h4 class="fav-style">
                        @if (Auth::check() && Auth::user()->buyer)
                            <a href="javascript:void(0)" data-product="{{ $product->id }}" @if (isset($product->whishlist)) data-wishlist='{{ $product->whishlist->id }}' class="addBtn remove_to_wishlist wishlist-added" @else class="addBtn add_to_wishlist" @endif
                                data-buyer="{{ Auth::user()->buyer->id ?? 0 }}">
                                Add to Favorites
                                <i class="fa fa-heart"></i>
                            </a>
                        @endif
                    </h4>

                    <div class="detl detl-ar">
                        <h4>
                            @if ($product->discount_price > 0)
                                @php
                                    $final_price_pecnt_amnt = ($product->discount_price / 100) * $setting->service_charges;
                                    $final_price = $product->discount_price + $final_price_pecnt_amnt;
                                @endphp
                                ${{ sprintf('%.2f', $final_price) }}
                            @else
                                @php
                                    $final_price_pecnt_amnt = ($product->product_current_price / 100) * $setting->service_charges;
                                    $final_price = $product->product_current_price + $final_price_pecnt_amnt;
                                @endphp
                                ${{ sprintf('%.2f', $final_price) }}
                            @endif
                            <small>
                                {{-- @if ($product->shipping_charges > 0)
                                    +
                                    ${{ $product->shipping_charges }}(Shipping Charges)
                                @endif --}}
                            </small>
                        </h4>
                        <br>
                        @if ($product->qty > 0)
                            <div class="stockLbl inStockLbl">
                                <label class="badge badge-success">In Stock</label>
                            </div>
                        @else
                            <div class="stockLbl outOfStockLbl">
                                <label class="badge badge-danger">Out of Stock</label>
                            </div>
                        @endif
                        <strong>Description</strong>
                        <p>{{ $product->description }}</p>
                        <div class="product-button">
                            @if (Auth::check() && Auth::user()->seller)

                            @else
                                {{-- <a href="{{ url('/' . $product->id . '/checkout') }}" class=" orangeBtn" id="buyNow">
                                    Buy Now
                                </a> --}}
                                @if ($product->qty > 0 && $product->price > 0)
                                    <form action="{{ url('cart/store', $product->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="orangeBtn">Add To Cart</button>
                                        <input type="hidden" class="form-control" value="1" style="width: 50%"><br>
                                    </form>
                                @endif

                                @if ($product->price == 0)
                                    <a href="javascript:void(0)" class="orangeBtn" data-toggle="modal"
                                        data-target="#addProducts"> Claim </a>
                                @endif

                            @endif

                        </div>
                    </div>
                    <hr>
                    <div class="media sellerPic">
                        <img src="{{ sellerProfilePicture($product->seller->profile_picture) }}" class="mr-3"
                            alt="" class="img-fluid">
                        <div class="media-body">
                            <h5 class="mt-0">{{ $product->seller->name }}</h5>
                            <a href="{{ url('seller/profile') . '/' . $product->seller->id }}"
                                class="orangeBtn">Seller
                                Profile</a>
                            <!-- <a href="#" class="orangeBtn" data-toggle="modal" data-target="#exampleModalCenter">Contact Seller</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Add Review --}}
    <section class="reviewSec">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Add Review</h2>
                    <form id="form-review">
                        <div id="reviewAlert"></div>
                        @csrf
                        <div class="reviewForm col-sm-10">
                            <div class="formsetrvew form-group">
                                <label>Name *</label>
                                <input class="form-control" type="text" name="name">
                            </div>
                            <div class="formsetrvew form-group">
                                <label class="yreviw">Your review *</label>
                                <textarea class="form-control" name="text"></textarea>
                            </div>
                            <div class="formsetrvew form-group">
                                <label>Your rating *</label>
                                <span>
                                    <i data-value="1" class="fas fa-star"></i>
                                    <i data-value="2" class="fas fa-star"></i>
                                    <i data-value="3" class="fas fa-star"></i>
                                    <i data-value="4" class="fas fa-star"></i>
                                    <i data-value="5" class="fas fa-star"></i>
                                </span>
                            </div>
                            <input type="hidden" name="rating" id="rating" value="">
                            <button type="button" id="button-review" class="orangeBtn">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <h2>Reviews</h2>
                    <ul class="list-unstyled reviewComments">
                        @if (count($productReviews) > 0)
                            @foreach ($productReviews as $review)
                                <li class="media">
                                    <img src="{{ buyerProfilePicture(@$review->buyer->profile_picture) }}"
                                        class="mr-3 rounded-circle" alt="" width="50px">
                                    <div class="media-body">
                                        <ul class="star">
                                            @for ($i = 1; $i <= $review->rating; $i++)
                                                <i class="fas fa-star star"></i>
                                            @endfor
                                            @for ($j = 1; $j <= 5 - $review->rating; $j++)
                                                <i class="fas fa-star gray"></i>
                                            @endfor
                                        </ul>
                                        <h5 class="mb-1">{{ $review->author ?? '' }} </h5>
                                        <p> {{ $review->description ?? '' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <h5>No Review at the moment!</h5>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>



    <section class="relatedProducts">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Related Products</h2>
                </div>
                @if (!empty($mightAlsoLike) && count($mightAlsoLike) > 0)
                    <div class="col-lg-12">
                        <div class="proCarousel">
                            @foreach ($mightAlsoLike as $product)
                                <a href="{{ route('product-details.show', [$product->id]) }}" class="proThumb">
                                    <img src="{{ productImage(@$product->product_image) }}"
                                        alt="{{ $product->product_name }}">
                                    <div class="content">
                                        <h4>{{ \Illuminate\Support\Str::limit($product->description, 50, $end = '...') }}
                                        </h4>
                                        <span>
                                            @if ($product->discount_price > 0)
                                                @php
                                                    $final_price_pecnt_amnt = ($product->discount_price / 100) * $setting->service_charges;
                                                    $final_price = $product->discount_price + $final_price_pecnt_amnt;
                                                @endphp

                                                ${{ sprintf('%.2f', $final_price) }}

                                            @else
                                                @php
                                                    $final_price_pecnt_amnt = ($product->product_current_price / 100) * $setting->service_charges;
                                                    $final_price = $product->product_current_price + $final_price_pecnt_amnt;
                                                @endphp

                                                ${{ sprintf('%.2f', $final_price) }}
                                            @endif
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="col-lg-12">
                        <h5>No Related Product Found!</h5>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProducts" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-sm-5">
                    {{-- <div class="alert alert-primary alert-dismissible fade show adSuccess" role="alert"
                        style="display:none">
                        <strong>Success!</strong> Your Product Added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show adDanger" role="alert" style="display:none">
                        <strong>Opps!</strong> Unexpected error occured please check errors below.
                        <span id="opps_span"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> --}}

                    <div class="col-md-12">
                        <h3>Billing Detail</h3>
                    </div>

                    @if (Auth::check() && (Auth::user()->role_id = 3))
                        <form action="{{ url('product-claim') }}" class="row formStyle billingForm" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ request()->route('id') }}">
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="First Name"
                                    name="first_name" value="@if (!empty($shippingAddress)) {{ $shippingAddress->first_name }} @endif" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="Last Name"
                                    name="last_name" value="@if (!empty($shippingAddress)) {{ $shippingAddress->last_name }} @endif" required>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Company Name(Optional)"
                                    name="company_name" value="@if (!empty($shippingAddress)) {{ $shippingAddress->company_name }} @endif">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control requiredField" placeholder="Street Line"
                                    name="address1" value="@if (!empty($shippingAddress)) {{ $shippingAddress->address1 }} @endif" required>
                            </div>
                            {{-- <div class="col-md-12">
                                <input type="text" class="form-control requiredField" name="address2"
                                    placeholder="Street Line" value="@if (!empty($shippingAddress)) {{ $shippingAddress->address2 }} @endif"
                                    required>
                            </div> --}}
                            <div class="col-md-6">
                                <select name="country" class="form-control countries requiredField"
                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->country }} @endif" required>
                                    <option value="">Select Country*</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" @if (!empty($shippingAddress) && $shippingAddress->country == $country->id) selected @endif>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <select name="state" class="form-control states requiredField" required>
                                    <option value="@if (!empty($state)) {{ $state->id }} @endif" selected>
                                        @if (!empty($state))
                                        {{ $state->name }} @else Select State* @endif
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-control cities requiredField" required>
                                    {{-- <option value="">Select City</option> --}}
                                    <option value="@if (!empty($city)) {{ $city->id }} @endif" selected>
                                        @if (!empty($city))
                                        {{ $city->name }} @else Select City* @endif
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control requiredField" name="zip_code" placeholder="Zip Code"
                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->zip_code }} @endif" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" name="phone_number"
                                    placeholder="Phone Number" name="phone_no" value="@if (!empty($shippingAddress)) {{ $shippingAddress->phone_no }} @endif" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="Email" name="email"
                                    value="@if (!empty($shippingAddress)) {{ $shippingAddress->email }} @endif" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="orangeBtn" id="addressBtn">Save and
                                    Continue</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ url('product-claim') }}" class="row formStyle billingForm" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ request()->route('id') }}">
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="First Name*"
                                    name="first_name" value="" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="Last Name*"
                                    name="last_name" value="" required>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Company Name(Optional)"
                                    name="company_name" value="">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control requiredField" placeholder="Street Line*"
                                    name="address1" value="" required>
                            </div>
                            {{-- <div class="col-md-12">
                            <input type="text" class="form-control requiredField"
                                placeholder="Street Line*" name="address2" value="" required>
                        </div> --}}
                            <div class="col-md-6">
                                <select name="country" class="form-control countries requiredField" required>
                                    <option value="">Select Country*</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="state" class="form-control states requiredField" required>
                                    <option value="">Select State*</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-control cities requiredField" required>
                                    <option value="">Select City*</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control requiredField" name="zip_code"
                                    placeholder="Zip Code*" value="" required>
                            </div>

                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" name="phone_number"
                                    placeholder="Phone Number*" name="phone_no" value="" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control requiredField" placeholder="Email*" name="email"
                                    value="" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="orangeBtn" id="addressBtn">Save and
                                    Continue</button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')
    <script src="{{ asset('front/js/location.js') }}"></script>
    <script>
        (function() {
            const currentImage = document.querySelector('#currentImage');
            const images = document.querySelectorAll('.product-section-thumbnail');
            images.forEach((element) => element.addEventListener('click', thumbnailClick));

            function thumbnailClick(e) {
                currentImage.classList.remove('active');
                currentImage.addEventListener('transitionend', () => {
                    currentImage.src = this.querySelector('img').src;
                    currentImage.classList.add('active');
                })
                images.forEach((element) => element.classList.remove('selected'));
                this.classList.add('selected');
            }
        })();

        $('doccment').ready(function() {
            $('.option').on('change', function() {
                let id = $(this).val();
                //  alert(id);
                if (id !== "") {
                    $.ajax({
                        url: "{{ url('checkProductPrice') }}",
                        type: "Get",
                        data: {
                            product_option_id: id,
                            product_id: {{ $product->id }}
                        },
                        success: function(data) {
                            // console.log(data);
                            if (data > 0) {
                                let price = $('#price').html();
                                $('#price').html(data);
                                $('#cart_price').val(data);
                            } else {
                                $('#price').html(data);
                            }

                        }
                    })
                }

            });

        })

        $(document).ready(function() {
            /* 1. Visualizing things on Hover - See next part for action on click */
            $('.reviewForm span i').on('mouseover', function() {
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('i').each(function(e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function() {
                $(this).parent().children('i').each(function(e) {
                    $(this).removeClass('hover');
                });
            });


            $('.reviewForm span i').on('click', function() {
                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                //console.log(onStar)
                var stars = $(this).parent().children('i');
                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('star');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('star');
                    $('#rating').val(parseInt(onStar));
                }
            });
        })

        $('#button-review').on('click', function() {

            $.ajax({
                url: '{{ url("/product/review/$product_id") }}',
                type: 'post',
                dataType: 'json',
                data: $("#form-review").serialize(),

                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['error']) {
                        $('#reviewAlert').after(
                            '<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' +
                            json['error'] + '</div>');
                    }

                    if (json['success']) {
                        $('#reviewAlert').after(
                            '<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' +
                            json['success'] + '</div>');

                        $('input[name=\'name\']').val('');
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').prop('checked', false);
                        $('span .fas').removeClass('star');
                    }
                }
            });
        });

        $(document).ready(function() {
            // $('#buyNow').on('click', function() {
            //     let url = "{{ url('/$product->id/checkout') }}";
            //     window.location = url;
            // });

            $('.billingForm').on('submit', function() {
                setTimeout(() => {
                    location.reload();
                }, 3000);
            });
        });
    </script>

    {{-- @if (session()->has('downloadfile')) --}}

    {{-- window.location.href = "{{ session()->get('downloadfile') }}"; --}}

    {{-- @endif --}}

@endsection
