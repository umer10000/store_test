<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach ($mightAlsoLike as $product)
                <a href="{{ route('shop.show', $product->slug ?? $product->products[0]->slug) }}" class="might-like-product">
                    <img src="{{ productImage($product->product_image ?? $product->products[0]->product_image) }}" alt="product">
                    <div class="might-like-product-name">{{ $product->product_name ?? $product->products[0]->product_name }}</div>
                    <div class="might-like-product-price">{{ $product->product_current_price ?? $product->products[0]->product_current_price }}</div>
                </a>
            @endforeach

        </div>
    </div>
</div>
