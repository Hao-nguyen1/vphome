@php
    $name = $post->languages->first()->pivot->name;
    $canonical = write_url($post->languages->first()->pivot->canonical);
    $image = image($post->image);
    $description = cut_string_and_decode($post->languages->first()->pivot->description, 100);
@endphp
<div class="product-item-2 product">
    <a href="{{ $canonical }}" class="image img-scaledown img-zoomin"><img src="{{ $image }}" alt="{{ $name }}"></a>
    <div class="info">
        <div class="info-wrapper">
            <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
            <div class="description">
                {{ $description }}
            </div>
        </div>
    </div>
</div>