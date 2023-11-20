@extends('front.layout.app')

@section('title', 'Wishlist')


@section('content')
<section class="banner banner2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wishlist</h2>
            </div>
        </div>
    </div>
</section>
<section class="shopPage">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            @if($products->count() > 0)
                            @foreach($products as $product)
                            <div class="col-md-4">
                                <div class="shopEyelashes">
                                    <a href="{{ route('shop.show', $product->product->slug) }}">
                                        <figure><img src="{{ productImage($product->product->product_image) }}" class="img-fluid" alt="img"></figure>
                                        <h2>{{ $product->product->product_name }}</h2>
                                    </a>
                                    <a href="{{ route('shop.show', $product->product->slug) }}" class="addCart"><span><img src="{{asset('front/images/payment.png')}}" class="img-fluid" alt="img"></span>Add to cart</a>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection