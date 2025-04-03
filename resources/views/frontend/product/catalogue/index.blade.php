@extends('frontend.homepage.layout')
@section('content')
    <div class="product-catalogue page-wrapper">
        @include('frontend.component.breadcrumb', ['model' => $productCatalogue, 'breadcrumb' => $breadcrumb])
        <div class="uk-container uk-container-center mt20">
           
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-1-4 uk-hidden-small">
                        <div class="aside">
                            @if(isset($categories['categories']))
                            <div class="aside-category">
                                <div class="aside-heading">Danh mục sản phẩm</div>
                                <div class="aside-body">
                                    <ul class="uk-list uk-clearfix">
                                        @foreach($categories['categories']->object as $category)
                                        @php
                                            $name = $category->languages->first()->pivot->name;
                                            $canonical = write_url($category->languages->first()->pivot->canonical);
                                        @endphp
                                        <li><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            <div class="aside-category aside-product mt20">
                                <div class="aside-heading">Sản phẩm nổi bật</div>
                                <div class="aside-body">
                                    @foreach($widgets['products-hl']->object as $product)
                                    @php
                                        $name = $product->languages->first()->pivot->name;
                                        $canonical = write_url($product->languages->first()->pivot->canonical);
                                        $image  = $product->image;
                                        $price = getPrice($product);
                                    @endphp
                                    <div class="aside-product uk-clearfix">
                                        <a href="" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                        <div class="info">
                                            <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                            {!! $price['html'] !!}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="uk-width-large-3-4">
                        <div class="wrapper ">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between mb20">
                                <h1 class="heading-2"><span>{{ $productCatalogue->languages->first()->pivot->name }}</span></h1>
                                @include('frontend.product.catalogue.component.filter')
                            </div>
                            @include('frontend.product.catalogue.component.filterContent')
                            @if(!is_null($products))
                                <div class="product-list">
                                    <div class="uk-grid uk-grid-medium">
                                        @foreach($products as $product)
                                            <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb20">
                                                @include('frontend.component.product-item', ['product'  => $product])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="uk-flex uk-flex-center">
                                    @include('frontend.component.pagination', ['model' => $products])
                                </div>
                            @endif
                            @if(!empty($productCatalogue->languages->first()->pivot->description))
                                <div class="product-catalogue-description">
                                    {!! $productCatalogue->languages->first()->pivot->description !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

