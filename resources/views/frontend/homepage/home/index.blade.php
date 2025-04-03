@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-main-slide">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-2-3">
                        @include('frontend.component.slide')
                    </div>
                    <div class="uk-width-large-1-3">
                        @if(count($slides['banner']['item']))
                        <div class="banner-wrapper">
                            <div class="uk-grid uk-grid-small">
                                @foreach($slides['banner']['item'] as $key => $val)
                                    <div class="uk-width-small-1-2 uk-width-medium-1-1">
                                        <div class="banner-item">
                                            <a href="{{ $val['canonical'] }}" title="{{ $val['description'] }}"><img src="{{ $val['image'] }}" alt="{{ $val['image'] }}"></a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(isset($widgets['flash-sale']))
            <div class="panel-flash-sale" id="#flash-sale">
                <div class="uk-container uk-container-center">
                    <div class="main-heading">
                        <div class="panel-head">
                            <h2 class="heading-1"><span>{{ $widgets['flash-sale']->name }}</span></h2>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            @foreach ($widgets['flash-sale']->object as $key => $product)
                                <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                                    @include('frontend.component.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="panel-general page">
            <div class="uk-container uk-container-center">
                @if(isset($widgets['product']->object) && count($widgets['product']->object))
                    @foreach($widgets['product']->object as $key => $category)
                    @php
                        $catName = $category->languages->first()->pivot->name;
                        $catCanonical = write_url($category->languages->first()->pivot->canonical)
                    @endphp
                    <div class="panel-product">
                        <div class="main-heading">
                            <div class="panel-head">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <h2 class="heading-1"><a href="{{ $catCanonical }}" title="{{ $catName }}">{{ $catName }}</a></h2>
                                    <a href="{{ $catCanonical }}" class="readmore">Tất cả sản phẩm</a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if(count($category->products))
                            <div class="uk-grid uk-grid-medium">
                                @foreach($category->products as $index => $product)
                                <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                                    @include('frontend.component.product-item', ['product' => $product])
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if(isset($widgets['posts']->object))
            @foreach($widgets['posts']->object as $key => $val)
            @php
                $catName = $val->languages->first()->pivot->name;
                $catCanonical = write_url($val->languages->first()->pivot->canonical);
            @endphp
            <div class="panel-news">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <h2 class="heading-2"><span><?php echo $catName ?></span></h2>
                    </div>
                    <div class="panel-body">
                        @if(count($val->posts))
                        <div class="uk-grid uk-grid-medium">
                            @foreach($val->posts  as $post)
                            @php
                                $name = $post->languages->first()->pivot->name;
                                $canonical = write_url($post->languages->first()->pivot->canonical);
                                $createdAt = convertDateTime($post->created_at, 'd/m/Y');
                                $description = cutnchar(strip_tags($post->languages->first()->pivot->description), 100);
                                $image = $post->image;
                            @endphp
                            <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5">
                                <div class="news-item">
                                    <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                    <div class="info">
                                        <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                        <div class="description">{!! $description !!}</div>
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <a href="{{ $canonical }}" class="readmore">Xem thêm</a>
                                            <span class="created_at">{{ $createdAt }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif

    </div>
@endsection
