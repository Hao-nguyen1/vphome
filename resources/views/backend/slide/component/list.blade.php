<div class="ibox">
    <div class="ibox-title slide-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>Danh sách Slides</h5>
            <button type="button" class="addSlide btn">Thêm slide</button>
        </div>
    </div>
    @php
        $slides = old('slide', ($slideItem) ?? []);
        $i = 1;
    @endphp

    <div class="ibox-content">
        <div  id="sortable" class="row slide-list sortui ui-sortable">
            <div class="text-danger slide-notification {{ (count($slides) > 0) ? 'hidden' : '' }}">Chưa có hình ảnh nào được chọn....</div>
           
            @if(is_array($slides) && count($slides) )
                @foreach($slides['image'] as $key => $val)
                @php
                    $image = image($val);
                    $description = $slides['description'][$key];
                    $canonical = $slides['canonical'][$key];
                    $name = $slides['name'][$key];
                    $alt = $slides['alt'][$key];
                    $window = (isset($slides['window'][$key])) ? $slides['window'][$key] : '';
                @endphp
                <div class="col-lg-12 ui-state-default">
                    <div class="slide-item mb20">
                        <div class="row custom-row">
                            <div class="col-lg-3 mb-10">
                                <span class="slide-image img-cover">
                                    <img src="{{ $image }}" alt="">
                                    <input type="hidden" name="slide[image][]" value="{{ $val }}">
                                    <span class="deleteSlide btn btn-danger"><i class="fa fa-trash"></i></span>
                                    <span class="select-image">Chọn Ảnh</span>
                                </span>
                            </div>
                            <div class="col-lg-9">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab{{ $i }}" aria-expanded="true"> Thông tin chung</a></li>
                                        <li class=""><a data-toggle="tab" href="#tab{{ $i + 1 }}" aria-expanded="false">SEO</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="tab{{$i}}" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="label-text mb5">Mô tả</div>
                                                <div class="form-row mb10">
                                                    <textarea name="slide[description][]" class="form-control">{{ $description }}</textarea>
                                                </div>
                                                <div class="form-row form-row-url">
                                                    <input 
                                                        type="text" 
                                                        name="slide[canonical][]" 
                                                        class="form-control"
                                                        placeholder="URL"
                                                        value="{{ $canonical }}"
                                                    >
                                                    <div class="overlay">
                                                        <div class="uk-flex uk-flex-middle">
                                                            <label for="input_{{ $i }}">Mở trong tab mới</label>
                                                            <input 
                                                                type="checkbox"
                                                                name="slide[window][]"
                                                                value="_blank"
                                                                {{  
                                                                    ($window == '_blank' ) ? 'checked' : ''
                                                                }}
                                                                id="input_{{ $i }}"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab{{ $i + 1 }}" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="label-text mb5">Tiêu đề ảnh</div>
                                                <div class="form-row form-row-url slide-seo-tab">
                                                    <input 
                                                        type="text" 
                                                        name="slide[name][]" 
                                                        class="form-control"
                                                        placeholder="Tiêu đề ảnh"
                                                        value="{{ $name }}"
                                                    >
                                                </div>
                                                <div class="label-text mb5 mt12">Mô tả ảnh</div>
                                                <div class="form-row form-row-url slide-seo-tab">
                                                    <input 
                                                        type="text" 
                                                        name="slide[alt][]" 
                                                        class="form-control"
                                                        placeholder="Mô tả ảnh"
                                                        value="{{ $alt }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $i+= 2;
                @endphp
                @endforeach
            @endif
        </div>
    </div>
</div>
