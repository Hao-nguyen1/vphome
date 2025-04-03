<div class="filter-content filter-minimize">
    <div class="filter-overlay">
        <div class="filter-close">
            <i class="fi fi-rs-cross"></i>
        </div>
        <div class="filter-content-container">
            {{-- @dd($filters) --}}
            @if(!is_null($filters))
                @foreach($filters as $key => $val)
                @php
                    $catName = $val->languages->first()->pivot->name;
                    if(is_null($val->attributes) || count($val->attributes) == 0) continue;
                @endphp
                <div class="filter-item">
                    <div class="filter-heading">{{ $catName }}</div>
                    @if(count($val->attributes))
                    <div class="filter-body">
                        @foreach($val->attributes as $item)
                        @php
                            $attributeName = $item->languages->first()->pivot->name;
                            $id = $item->id;
                        @endphp
                        <div class="filter-choose">
                            <input 
                                type="checkbox" 
                                id="attribute-{{ $id }}" 
                                class="input-checkbox filtering filterAttribute"
                                value="{{ $id }}"
                                data-group= "{{ $val->id }}"
                            >
                            <label for="attribute-{{ $id }}">{{ $attributeName }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            @endif
            <div class="filter-item filter-price slider-box">
                <div class="filter-heading" for="priceRange">Lọc Theo Giá:</div>
                <div class="filter-price-content">
                    <input type="text" id="priceRange" readonly="" class="uk-hidden">
                    <div id="price-range" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 100%;"></span></div>
                </div>
                <div class="filter-input-value mt5">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <input type="text" class="min-value input-value" value="0đ">
                        <input type="text" class="max-value input-value" value="20.000.000đ">
                    </div>
                </div>
            </div>
            <div class="filter-input-value-mobile mt5">
                <div class="filter-heading" for="priceRange">Lọc Theo Giá:</div>
                <a type="text" class="input-value" data-from="0" data-to="499.999">Dưới 500.000đ</a>
                <a type="text" class="input-value" data-from="500.000" data-to="1.000.000">Từ 500-1 triệu</a>
                <a type="text" class="input-value" data-from="1.000.000" data-to="2.000.000">Từ 1-2 triệu</a>
                <a type="text" class="input-value" data-from="2.000.000" data-to="4.000.000">Từ 2-4 triệu</a>
                <a type="text" class="input-value" data-from="4.000.000" data-to="7.000.000">Từ 4-7 triệu</a>
                <a type="text" class="input-value" data-from="7.000.000" data-to="13.000.000">Từ 7-13 triệu</a>
                <a type="text" class="input-value" data-from="13.000.000" data-to="20.000.000">Từ 13-20 triệu</a>
            </div>
            {{-- <div class="filter-item filter-category">
                <div class="filter-heading">Tình trạng sản phẩm</div>
                <div class="filter-body">
                    <div class="filter-choose">
                        <input id="input-availble" type="checkbox" name="stock[]" value="1" class="input-checkbox filtering">
                        <label for="input-availble">Còn hàng</label>
                    </div>
                    <div class="filter-choose">
                        <input id="input-outstock" type="checkbox" name="stock[]" value="0" class="input-checkbox filtering">
                        <label for="input-outstock">Hết Hàng</label>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="filter-review">
                <div class="filter-heading">Lọc theo đánh giá</div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input id="input-rate-5" type="checkbox" name="rate[]" value="5" class="input-checkbox filtering">
                    <label for="input-rate-5 uk-flex uk-flex-middle">
                        <div class="filter-star">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(5)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input id="input-rate-5" type="checkbox" name="rate[]" value="4" class="input-checkbox filtering">
                    <label for="input-rate-5 uk-flex uk-flex-middle">
                        <div class="filter-star">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(4)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input id="input-rate-5" type="checkbox" name="rate[]" value="3" class="input-checkbox filtering">
                    <label for="input-rate-5 uk-flex uk-flex-middle">
                        <div class="filter-star">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(3)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input id="input-rate-5" type="checkbox" name="rate[]" value="2" class="input-checkbox filtering">
                    <label for="input-rate-5 uk-flex uk-flex-middle">
                        <div class="filter-star">
                            <i class="fi-rs-star"></i>
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(2)</span>
                </div>
                <div class="filter-choose uk-flex uk-flex-middle">
                    <input id="input-rate-5" type="checkbox" name="rate[]" value="1" class="input-checkbox filtering">
                    <label for="input-rate-5 uk-flex uk-flex-middle">
                        <div class="filter-star">
                            <i class="fi-rs-star"></i>
                        </div>
                    </label>
                    <span class="totalProduct ml5 mb5">(1)</span>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<input type="hidden" class="product_catalogue_id" value="{{ $productCatalogue->id }}">