@extends('front.layout.app')
@section('title', 'Home')
@section('content')

    <!-- Begin: Main Slider -->
    <div class="mainBan"
        style="background: url({{ url($banner->banner_img ?? '') }}) top left/cover fixed no-repeat;">
        <h2>K7 STORE</h2>
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


    <section class="dealZoneSec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>{{ isset($firstSection->heading) ? $firstSection->heading : '' }}</h2>
                </div>
            </div>
            <div class="row">
                @if (isset($firstSection->heading))
                    @forelse ($firstSection->components as $component)
                        <div class="col-md-3">
                            <h4>{{ $component->title }}</h4>
                            <p>{{ $component->description }}</p>
                        </div>
                    @empty
                        <div class="col-md-3">
                            <h4>Buy Or Sell With Ease</h4>
                            <p>We make buying and selling gear online easy so you can focus on one thing-making music.</p>
                        </div>
                        <div class="col-md-3">
                            <h4>Endless Gear to Discover</h4>
                            <p>From vintage synths to rare guitars, find exactly what you need or something unexpected.</p>
                        </div>
                        <div class="col-md-3">
                            <h4>A Team That Has Your Back</h4>
                            <p>Buy and sel with confidence. One team of gear experts is here to help if you need a hand</p>
                        </div>
                        <div class="col-md-3">
                            <h4>A Community of Music Makers</h4>
                            <p>From rock stars to local music shops, buyers & sellers from al over the world call Reverb
                                home.
                            </p>
                        </div>
                    @endforelse
                @endif

            </div>
        </div>
    </section>

    <section class="topCategoriesSection">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Top Categories</h2>
                </div>
            </div>
            <div class="row">
                @forelse($topCategories as $category)
                    <div class="col-xl col-sm-6 mb-4">
                        <div class="categoryMain">
                            @if ($category->parent_id == 0)
                                <a href="{{ route('all-products.index', ['mainCategory' => $category->id]) }}">
                                    <div class="categoryImg">
                                        <img src="{{ categoryImage(@$category->category_image) }}" class="img-fluid"
                                            loading="lazy">
                                    </div>
                                    <h5>{{ $category->name }}</h5>
                                </a>
                            @else
                                <a href="{{ route('all-products.index', ['category' => $category->id]) }}">
                                    <div class="categoryImg">
                                        <img src="{{ categoryImage(@$category->category_image) }}" class="img-fluid"
                                            loading="lazy">
                                    </div>
                                    <h5>{{ $category->name }}</h5>
                                </a>
                            @endif

                        </div>
                    </div>
                @empty

                @endforelse

    </section>

    <section class="relatedProSec relatedChnge">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="title text-center fr-50 ">Shop Now</h2>
                </div>
                <div class="col-lg-10">
                    <nav>
                        <ul class="nav nav-pills" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one-pane" role="tab"
                                    aria-controls="one-pane" aria-selected="true">All</a>
                            </li>
                            @forelse($productsByCategories as $index => $categories)
                                <li class="nav-item">
                                    <a class="nav-link" id="two-tab" data-toggle="tab"
                                        href="#two-pane_{{ $index }}" role="tab" aria-controls="two-pane"
                                        aria-selected="false">{{ $categories->name }}</a>
                                </li>
                            @empty
                            @endforelse

                        </ul>
                    </nav>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="one-pane" role="tabpanel" aria-labelledby="one-tab">
                            <div class="row">
                                @php
                                    $allCounter = 0;
                                @endphp
                                @forelse($products as $product)
                                    @php
                                        $allCounter++;
                                    @endphp
                                    <div class="col-lg-3 col-md-3 col-6">
                                        <a href="{{ url('product-details') . '/' . $product->id }}"
                                            class="proThumb">
                                            <img src="{{ productImage(@$product->product_image) }}" alt=""
                                                loading="lazy">
                                            <div class="content">
                                                <h4>{{ $product->product_name }}</h4>
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
                                                <strong>Condition:
                                                    <span>{{ ucfirst($product->product_condition) }}</span></strong>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                @endforelse

                            </div>
                            <div class="vew-btn">
                                <a href="{{ url('all-products') }}" class="orangeBtn">view all</a>
                            </div>
                        </div>
                        @forelse($productsByCategories as $index => $categories)
                            <div class="tab-pane fade show" id="two-pane_{{ $index }}" role="tabpanel"
                                aria-labelledby="one-tab">
                                <div class="row">
                                    @php
                                        $catCounter = 0;
                                    @endphp
                                    @forelse($categories['products'] as $key => $product)
                                        @php
                                            $catCounter++;
                                        @endphp
                                        <div class="col-lg-3 col-md-3 col-6">
                                            <a href="{{ url('product-details') . '/' . $product->id }}"
                                                class="proThumb">
                                                <img src="{{ productImage(@$product->product_image) }}" loading="lazy"
                                                    alt="{{ $product->product_name }}"
                                                    style="height: 195px;width: 182px">
                                                <div class="content">
                                                    <h4>{{ $product->product_name }}</h4>
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
                                                    <strong>Condition:
                                                        <span>{{ ucfirst($product->product_condition) }}</span></strong>
                                                </div>
                                            </a>
                                        </div>
                                        {{-- @if ($catCounter % 5 == 0)
                                            <div class="w-100"></div>
                                        @endif --}}
                                    @empty

                                    @endforelse
                                    @forelse($categories['subCategoryproduct'] as $key => $product)
                                        @php
                                            $catCounter++;
                                        @endphp
                                        <div class="col-lg-3 col-md-3 col-6">
                                            <a href="{{ url('product-details') . '/' . $product->id }}"
                                                class="proThumb">
                                                <img src="{{ productImage(@$product->product_image) }}" loading="lazy"
                                                    alt="{{ $product->product_name }}"
                                                    style="height: 195px;width: 182px">
                                                <div class="content">
                                                    <h4>{{ $product->product_name }}</h4>
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
                                                    <strong>Condition:
                                                        <span>{{ ucfirst($product->product_condition) }}</span></strong>
                                                </div>
                                            </a>
                                        </div>
                                        {{-- @if ($catCounter % 5 == 0)
                                            <div class="w-100"></div>
                                        @endif --}}
                                    @empty

                                    @endforelse

                                </div>
                                @if ($categories->parent_id == 0)
                                    <div class="vew-btn">
                                        <a href="{{ url('all-products?mainCategory=') . $categories->id }}"
                                            class="orangeBtn">view
                                            all
                                        </a>
                                    </div>
                                @else
                                    <div class="vew-btn">
                                        <a href="{{ url('all-products?category=') . $categories->id }}"
                                            class="orangeBtn">view
                                            all
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @empty
                        @endforelse


                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletterSection mb-5">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-md-6 p-0">
                    <div class="newsletterSliderMain">
                        @if (isset($secondSection->images))
                            @forelse ($secondSection->images as $image)
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('uploads/cms/slider/' . $image->image) }}"
                                            class="img-fluid" />
                                    </figure>
                                </div>
                            @empty
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-2.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-3.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-4.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-5.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                            @endforelse
                        @endif

                    </div>
                </div>
                <div class="col-md-6 p-0">
                    <div class="newsletterContentMain">
                        <div class="newsletterContentInner">
                            <h4>{{ isset($secondSection->heading) ? $secondSection->heading : '' }}</h4>
                            <p>{{ isset($secondSection->components[0]->title) ? $secondSection->components[0]->title : '' }}
                            </p>

                            <form class="form-inline newsletterForm" onsubmit="return false;">
                                <label class="sr-only" for="inlineFormInputName2">Name</label>
                                <input type="email" class="form-control" id="email_newsletter"
                                    placeholder="Enter Email Address">
                                <button type="submit" class="orangeBtn" id="newsletterSubmit">Submit</button>
                            </form>

                            <span>{{ isset($secondSection->components[1]->title) ? $secondSection->components[1]->title : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="specialDealSection">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="fr-50">Special Deals</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="specialDealSlider">
                        @forelse ($dealsProducts as $dealsProduct)
                            <div class="slider-main">
                                <a href="{{ url('product-details') . '/' . $dealsProduct->product_id }}"
                                    class="proThumb thumbchnge">
                                    <img src="{{ productImage(@$dealsProduct->product->product_image) }}" loading="lazy"
                                        style="width:243px;height:244px" class="img-fluid" />
                                    <div class="content">
                                        <h4>{{ $dealsProduct->product->product_name }}</h4>
                                        @php
                                            $d_final_price_pecnt_amnt = ($dealsProduct->product->discount_price / 100) * $setting->service_charges;
                                            $d_final_price = $dealsProduct->product->discount_price + $final_price_pecnt_amnt;
                                            
                                            $final_price_pecnt_amnt = ($dealsProduct->product->product_current_price / 100) * $setting->service_charges;
                                            $final_price = $dealsProduct->product->product_current_price + $final_price_pecnt_amnt;
                                        @endphp
                                        <span>
                                            {{-- {{ presentPrice($dealsProduct->product->discount_price) }} --}}
                                            ${{ sprintf('%.2f', $d_final_price) }}
                                            <del>
                                                {{-- {{ presentPrice($dealsProduct->product->product_current_price) }} --}}
                                                ${{ sprintf('%.2f', $final_price) }}
                                            </del>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @empty
                    </div>
                    <div class="noSpecialTxt text-center">
                        <p>No Special deals at the moment :((</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        </div>
    </section>
    <section class="advertiseSection advertiseChnge">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="fr-50">Get Featured</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="advertiseSlider">
                        <div class="slider-advertise">
                            <div class="row">
                                @forelse ($featuredads as $item)
                                    <div class="col-md-12 ad">
                                        <a href="javascript:;">
                                            <img src="{{ featuredAddImage($item->banner) }}" class="img-fluid w-100"
                                                loading="lazy" />
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-md-12 ad text-center">
                                        <p>No Banner Featured at the moment :((</p>
                                    </div>
                                    {{-- <div class="slider-advertise">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="#">
                                                    <img src="{{ asset('front/images/adss-1.png') }}"
                                                        class="img-fluid w-100" />
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('front/images/adss-2.png') }}"
                                                        class="img-fluid w-100" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('front/images/adss-3.png') }}"
                                                        class="img-fluid w-100" />
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('front/images/adss-4.png') }}"
                                                        class="img-fluid w-100" />
                                                </a>
                                            </div>
                                        </div>
                                    </div> --}}
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="newsletterSection">
        <div class="container-fluid p-0">
            @if (isset($thirdSection->heading))
                <div class="row m-0">
                    <div class="col-md-6 p-0">
                        <div class="newsletterSliderMain">

                            @forelse ($thirdSection->images as $image)
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('uploads/cms/slider/' . $image->image) }}"
                                            class="img-fluid" />
                                    </figure>
                                </div>
                            @empty
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-7.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-8.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-9.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                                <div class="slideInner">
                                    <figure>
                                        <img src="{{ asset('front/images/slide-10.jpg') }}" class="img-fluid" />
                                    </figure>
                                </div>
                            @endforelse

                        </div>
                    </div>
                    <div class="col-md-6 p-0">
                        <div class="newsletterContentMain">
                            <div class="newsletterContentInner">
                                <h4>{{ isset($thirdSection->heading) ? $thirdSection->heading : '' }}</h4>
                                <p>{!! isset($thirdSection->components[0]->description) ? $thirdSection->components[0]->description : '' !!}</p>
                                <button type="submit" class="orangeBtn mt-4"
                                    onclick="window.location='{{ url('register') }}'">Sell Your Gear</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
