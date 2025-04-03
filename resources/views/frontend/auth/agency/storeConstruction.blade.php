@extends('frontend.homepage.layout')
@section('content')
<div class="profile-container pt20 pb20">
    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium">
            <div class="uk-width-large-1-5">
                @include('frontend.auth.agency.components.sidebar')
            </div>
            <div class="uk-width-large-4-5">
                @php
                        $url = ($config['method'] == 'create') ? route('agency.construction.store') : route('agency.construction.update',[$construct->id, $queryUrl ?? '']);
                        $title = ($config['method'] == 'create') ? 'Thêm mới công trình' : 'Cập nhật công trình';
                    @endphp
                <div class="panel-head">
                    <h2 class="heading-2">{{ $title }}</h2>
                    <div class="description">
                        Quản lý thông tin chi tiết danh sách các công trình tại Omega Deco
                    </div>
                </div>
                <div class="panel-profile">
                    @include('backend.dashboard.component.formError')
                    <form action="{{ $url }}" method="post" class="box">
                        @csrf
                        <div class="panel-wrapper">
                            <div class="uk-grid uk-grid-medium ">
                                <div class="col-lg-4">
                                    <div class="ibox w">
                                        <div class="ibox-title">
                                            <h5>Thông tin chung</h5>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="agency_id" value="{{ $agency->id }}">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Chọn khách hàng<span class="text-danger"> (*)</span></div>
                                                        <select name="customer_id" class="input-text setupSelect2">
                                                            <option value="0">[Chọn khách hàng]</option>
                                                            @foreach($customers as $key => $val)
                                                            <option {{ 
                                                                $key == old('customer_id', (isset($construct->customer_id)) ? $construct->customer_id : '') ? 'selected' : '' 
                                                                }} value="{{ $key }}" >{{ $val }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Chọn Thành Phố<span class="text-danger"> (*)</span></div>
                                                        <select name="province_id" class="input-text setupSelect2">
                                                            <option value="0">[Chọn thành phố]</option>
                                                            @foreach($provinces as $key => $val)
                                                            <option {{ 
                                                                $val->code == old('province_id', (isset($construct->province_id)) ? $construct->province_id : '') ? 'selected' : '' 
                                                                }} value="{{ $val->code }}">{{ $val->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                    
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Xác nhận công trình<span class="text-danger"> (*)</span></div>
                                                        <select name="confirm" class="input-text setupSelect2">
                                                            @foreach(['pending' => 'Chưa xác nhận', 'confirmed' => 'Xác nhận'] as $key => $val)
                                                            <option {{ 
                                                                $key == old('confirm', (isset($construct->confirm)) ? $construct->confirm : '') ? 'selected' : '' 
                                                                }} value="{{ $key }}">{{ $val }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="ibox w">
                                        <div class="ibox-title">
                                            <h5>Thông tin công trình</h5>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row mb10">
                                                <div class="col-lg-8">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Tên công trình<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text"
                                                            name="name"
                                                            value="{{ old('name', ($construct->name) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Mã Công Trình<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text"
                                                            name="code"
                                                            value="{{ old('code', ($construct->code) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Chủ đầu tư<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text"
                                                            name="invester"
                                                            value="{{ old('invester', ($construct->invester) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Xưởng sản xuất<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text"
                                                            name="workshop"
                                                            value="{{ old('workshop', ($construct->workshop) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-row mb20">
                                                        <div for="" class="control-label text-left">Điểm tích lũy<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text int"
                                                            name="point"
                                                            value="{{ old('point', ($construct->point) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="form-row">
                                                        <div for="" class="control-label text-left">Địa chỉ<span class="text-danger"> (*)</span></div>
                                                        <input
                                                            type="text"
                                                            class="input-text"
                                                            name="address"
                                                            value="{{ old('address',($construct->address) ?? '') }}"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ibox">
                                        <div class="ibox-title">
                                            <h5>Thông tin sản phẩm</h5>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="search-model-box construction-product-box">
                                                <i class="fa fa-search"></i>
                                                <input type="text" class="input-text search-model">
                                                <div class="ajax-search-result"></div>
                                            </div>
                                            <div class="row mb10 mt10">
                                                
                                                <div class="col-lg-4">
                                                    <strong>Tên sản phẩm</strong>
                                                </div>
                                                <div class="col-lg-3">
                                                    <strong>Hệ màu</strong>
                                                </div>
                                                <div class="col-lg-2">
                                                    <strong>Số lượng</strong>
                                                </div>
                                                <div class="col-lg-2">
                                                    <strong>Bảo hành</strong>
                                                </div>
                                                <div class="col-lg-1 text-right">
                                                    
                                                </div>
                                            
                                            </div>
                                            <div class="construction-product-result mt20">
                                                @php
                                                    $product = old('product', ($productConstruction) ?? null);
                                                @endphp
                    
                                                @if(!is_null($product) && count($product))
                                                    @foreach($product['name'] as $key => $val)
                                                        <div class="row uk-flex uk-flex-middle mb10 search-result-item" id="model-{{ $product['id'][$key] }}" data-modelid="{{ $product['id'][$key] }}">
                                                            <div class="col-lg-4">
                                                                <div class="form-row">
                                                                    <input 
                                                                        type="text"
                                                                        readonly
                                                                        class="form-control"
                                                                        value="{{ $val }}"
                                                                        name="product[name][]"
                                                                    >
                                                                    <input 
                                                                        type="hidden"
                                                                        name="product[id][]"
                                                                        value="{{ $product['id'][$key] }}"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-row">
                                                                    <input 
                                                                        type="text"
                                                                        name="product[color][]"
                                                                        class="form-control"
                                                                        value="{{ $product['color'][$key] }}"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-row">
                                                                    <input 
                                                                        type="text"
                                                                        name="product[quantity][]"
                                                                        class="form-control text-right int"
                                                                        value="{{ $product['quantity'][$key] }}"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="form-row">
                                                                    <input 
                                                                        type="text"
                                                                        name="product[warranty][]"
                                                                        class="form-control text-right int"
                                                                        value="{{ $product['warranty'][$key] }}"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <button type="button" class="remove-attribute btn btn-danger"><svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-Svg" style="display: block;"><path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path></svg></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mb15 mt15">
                            <button class="btn-create" type="submit" name="send" value="send">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    var province_id = ''
</script>