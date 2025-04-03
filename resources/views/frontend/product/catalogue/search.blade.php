@extends('frontend.homepage.layout')
@section('content')
    <div class="product-catalogue page-wrapper">
        <div class="uk-container uk-container-center mt20">
           
            <div class="panel-body">
                <h2 class="heading-1 mb20"><span>{{ $seo['meta_title'] }}</span></h2>
                @if(!is_null($products) && count($products))
                <div class="product-list">
                    <div class="uk-grid uk-grid-medium">
                        @foreach($products as $product)
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                            @include('frontend.component.product-item', ['product'  => $product])
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                    <div class="pt20 pb20">
                        Không có sản phẩm phù hợp....
                    </div>

                @endif
            </div>

        </div>
    </div>

@endsection

