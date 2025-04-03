@extends('frontend.homepage.layout')
@section('content')

<div class="product-container">
    @include('frontend.component.breadcrumb', ['model' => $productCatalogue, 'breadcrumb' => $breadcrumb])
    <div class="uk-container uk-container-center mt30">
        <div class="panel-body">
            @include('frontend.product.product.component.detail', ['product' => $product, 'productCatalogue' => $productCatalogue])
        </div>
    </div>
</div>
<div id="qrcode" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="qrcode-container">
            {!! $product->qrcode !!}
        </div>
    </div>
</div>
@endsection
