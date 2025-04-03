@php
    $name = $product->name;
    
    $canonical = write_url($product->canonical);
    $image = image($product->image);
    $price = getPrice($product);
    $catName = $productCatalogue->name;
    $review = getReview($product);
    
    $description = $product->description;
    $attributeCatalogue = $product->attributeCatalogue;
    $gallery = json_decode($product->album);
@endphp
<div class="panel-body">
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large-1-2">
            @if(!is_null($gallery))
            <div class="popup-gallery">
                <div class="swiper-container">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-wrapper big-pic">
                        <?php foreach($gallery as $key => $val){  ?>
                        <div class="swiper-slide" data-swiper-autoplay="2000">
                            <a href="{{ image($val) }}" data-uk-lightbox="{group:'my-group'}" class="image img-scaledown"><img src="{{ image($val) }}" alt="<?php echo $val ?>"></a>
                        </div>
                        <?php }  ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="swiper-container-thumbs">
                    <div class="swiper-wrapper pic-list">
                        <?php foreach($gallery as $key => $val){  ?>
                        <div class="swiper-slide">
                            <span  class="image img-scaledown"><img src="{{  image($val) }}" alt="<?php echo $val ?>"></span>
                        </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="uk-width-large-1-2">
            <div class="popup-product">
                <h1 class="title product-main-title"><span>{{ $name }}</span>
                </h1>
                <div class="rating">
                    <div class="uk-flex uk-flex-middle">
                        <div class="author">Đánh giá: </div>
                        <div class="star-rating">
                            <div class="stars" style="--star-width: 8{{ rand(1, 9) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="product-specs">
                    <div class="spec-row">Mã sản phẩm: <strong>{{ $product->code }}</strong></div>
                    <div class="spec-row">Tình Trạng: <strong>Còn hàng</strong></div>
                </div>

                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-large-1-2">
                        <div class="a-left">
                            {!! $price['html'] !!}
                            @if($price['price']  != $price['priceSale'])
                            <div class="price-save">
                                Tiết kiệm: <strong>{{ convert_price($price['price'] - $price['priceSale'], true) }}</strong> (<span style="color:red">-{{ $price['percent'] }}%</span>)
                            </div>
                            @endif
                        
                            @include('frontend.product.product.component.variant')
                            <div class="quantity mt10">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="quantitybox uk-flex uk-flex-middle">
                                        <div class="minus quantity-button">-</div>
                                        <input type="text" name="" value="1" class="quantity-text">
                                        <div class="plus quantity-button">+</div>
                                    </div>
                                    <div class="btn-group uk-flex uk-flex-middle">
                                        <div class="btn-item btn-1 addToCart" data-id="{{ $product->id }}">
                                            <a href="" title="">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-item btn-1 addToCart mobile" data-id="{{ $product->id }}">
                                <a href="" title="">Mua ngay</a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="a-right">
                            <div class="mb20"><strong>Dịch vụ của chúng tôi</strong></div>
                            <div class="panel-body">
                                <div class="right-item">
                                    <div class="label">Cam kết bán hàng</div>
                                    <div class="desc">✅Chính hãng có thẻ bảo hành đầy đủ</div>
                                </div>
                                <div class="right-item">
                                    <div class="label">CHĂM SÓC KHÁCH HÀNG</div>
                                    <div class="desc">✅Tư vấn nhiệt tình, lịch sự, trung thực</div>
                                </div>
                                <div class="right-item">
                                    <div class="label">CHÍNH SÁCH GIAO HÀNG</div>
                                    <div class="desc">✅Đồng kiểm →Thử hàng →Hài lòng thanh toán</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-description">
                    {!! $product->languages->first()->pivot->description !!}
                </div>
            </div>
        </div>
    </div>

    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large-3-4">
            <div class="product-wrapper">
                @include('frontend.product.product.component.general')
                @include('frontend.product.product.component.review', ['model' => $product, 'reviewable' => 'App\Models\Product'])
            </div>
        </div>
        <div class="uk-width-large-1-4 uk-visible-large">
            <div class="aside">
               
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
       
    </div>

    <div class="product-related">
        <div class="uk-container uk-container-center">
            <div class="panel-product">
                <div class="main-heading">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>Sản phẩm cùng danh mục</span></h2>
                        </div>
                    </div>
                </div>
                <div class="panel-body list-product">
                    @if(count($productCatalogue->products))
                    <div class="uk-grid uk-grid-medium">
                        @foreach($productCatalogue->products as $index => $product)
                        @if($index > 7) @break @endif
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                            @include('frontend.component.product-item', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="product-related">
        <div class="uk-container uk-container-center">
            <div class="panel-product">
                <div class="main-heading">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>Sản phẩm đã xem</span></h2>
                        </div>
                    </div>
                </div>
                <div class="panel-body list-product">
                    @if(!is_null($cartSeen) && isset($cartSeen) )
                    <div class="uk-grid uk-grid-medium">
                    @foreach($cartSeen as $key => $val)
                    @php
                        $name = $val->name;
                        $canonical = $val->options['canonical'];
                        $image = $val->options['image'];
                        $priceSeen = number_format($val->price, 0, ',', '.');
                    @endphp
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                       
                            <div class="product-item product">
                                 <a href="{{ $canonical }}" class="image img-scaledown img-zoomin"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                <div class="info">
                                    <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                   <div class="price">
                                        <div class="price-sale">{{ $priceSeen }}</div>
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
    </div>
   
</div>

<input type="hidden" class="productName" value="{{ $product->name }}">
<input type="hidden" class="attributeCatalogue" value="{{ json_encode($attributeCatalogue) }}">
<input type="hidden" class="productCanonical" value="{{ write_url($product->canonical) }}">

