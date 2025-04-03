@php
    $name = $product->languages->first()->pivot->name;
    $canonical = write_url($product->languages->first()->pivot->canonical);
    $image = image($product->image);
    $price = getPrice($product);
    $catName = $product->product_catalogues->first()->languages->first()->pivot->name;
    $review = getReview($product);
@endphp
<div class="best-seller-item">
    <div class="badge">Top 1 tại OmegaDeco</div>
    <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
    <div class="info">
        <div class="category-title">
            <span class="line-1"></span>
            <span class="line-2"></span>
            <span class="line-3"></span>
            <a href="{{ $canonical }}" title="{{ $name }}">{{ $catName }}</a>
        </div>
        <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
        <div class="deal">Deal siêu hời tại Omega</div>
        <div class="product-group">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                {!! $price['html'] !!}
            </div>
        </div>
        <div class="rating">
            <div class="uk-flex uk-flex-middle">
                <div class="star-rating">
                    <div class="stars" style="--star-width: {{ $review['star'] }}%"></div>
                </div>
                <span class="rate-number">({{ $review['count'] }} đánh giá)</span>
            </div>
        </div>
        
    </div>
</div>