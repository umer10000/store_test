@extends('front.layout.app')

@section('title', 'Products')

@section('content')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
    integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

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

</style>
<div class="container">
    @if (session()->has('success_message'))
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<div class="products-section container">
    <div class="text-center">
        <div class="products-header">
            <h1 class="stylish-heading">Search Results</h1>
        </div>

        <div class="products text-center row">
            @forelse ($products as $product)
                <div class="product col-md-3">
                    <a href="{{ url('product-details') . '/' . $product->id }}" class="proThumb thumbchnge">
                        <img src="{{ productImage(@$product->product_image) }}" loading="lazy"
                            style="width:243px;height:244px" class="img-fluid" />
                        <div class="content">
                            <h4>{{ $product->product_name }}</h4>
                            <span>{{ presentPrice($product->discount_price) }}
                                <del>{{ presentPrice($product->product_current_price) }}</del></span>
                        </div>
                    </a>
                </div>
            @empty
                <div style="text-align: left">No items found</div>
            @endforelse
        </div> <!-- end products -->
        <br>

        <div class="spacer"></div>
        {{ $products->appends(request()->input())->links() }}
    </div>
</div>

@endsection

@section('extra-js')
<!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
{{-- <script src="{{ asset('js/algolia.js') }}"></script> --}}
@endsection
