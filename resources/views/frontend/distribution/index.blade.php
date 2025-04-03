@extends('frontend.homepage.layout')
@section('content')
    <div class="page-breadcrumb background">      
        <div class="uk-container uk-container-center">
            <ul class="uk-list uk-clearfix">
                <li><a href="/"><i class="fi-rs-home mr5"></i>{{ __('frontend.home') }}</a></li>
                <li><a href="{{ route('distribution.list.index') }}" title="Hệ thống phân phối">Hệ thống phân phối</a></li>
                
            </ul>
        </div>
    </div>
    <div class="page-agency pt20 pb20">
        
        <div class="panel-agency mb50">
            <div class="uk-container uk-container-center">
                <div class="panel-head">
                    <h2 class="heading">
                        HỆ THỐNG NHÀ PHÂN PHỐI
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="uk-grid uk-grid-collapse uk-grid-match">
                        <div class="uk-width-large-1-3">
                            <div class="agency-search">
                                <form class="agency-search-form" method="get" action="" name="agency_search">
                                    <div class="form-row">
                                        <label>
                                            Tỉnh/Thành phố
                                        </label>
                                        <select name="cityid" id="city" class="city-select location agency-select setupSelect2 mb10" data-target="districts">
                                            @foreach($provinces as $key => $val)
                                                <option value="{{ $val->code }}" >
                                                    {{ $val->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row mt20">
                                        <label>
                                            Quận/Huyện
                                        </label>
                                        <div class="district-search">
                                            <select name="district" id="district" class="district-select agency-select setupSelect2 districts">
                                                <option value="0">
                                                    Chọn quận huyện
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="submit" name="" value="Tìm kiếm" class="agency-submit">
                                </form>
                            </div>
                            <div class="agency-list">
    
                                @if(!is_null($distributions))
                                    @foreach($distributions as $key => $val)
                                        <div class="agency-item" data-id = "{{ $val->id }}">
                                            <div class="title">
                                                {{ $val->name }}
                                            </div>
                                            <div class="address">
                                                {{ $val->address }}
                                            </div>
                                            <div class="email">
                                                {{ $val->email }}
                                            </div>
                                            <div class="phone">
                                                {{ $val->phone }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="uk-width-large-2-3">
                            <div class="agency-map">
                                @if(!is_null($distributions))
                                    @foreach($distributions as $key => $val)
                                        @if($key > 0) @break @endif
                                            {!! $val->map !!}
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
@endsection

<script>
    var province_id = '{{ (isset($order->province_id)) ? $order->province_id : old('province_id') }}'
    var district_id = '{{ (isset($order->district_id)) ? $order->district_id : old('district_id') }}'
</script>


