@extends('frontend.homepage.layout')
@section('content')
<div class="profile-container pt20 pb20">
    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium">
            <div class="uk-width-large-1-5">
                @include('frontend.auth.agency.components.sidebar')
            </div>
            <div class="uk-width-large-4-5">
                <div class="panel-head">
                    <h2 class="heading-2">Thêm mới khách hàng</h2>
                    <div class="description">
                        Quản lý thông tin chi tiết danh sách các công trình tại Omega Deco
                    </div>
                </div>
                <div class="panel-profile">
                    @include('backend.dashboard.component.formError')
                    <form action="{{ route('agency.customer.store') }}" method="post" class="box">
                        @csrf
                        <div class="panel-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="ibox w">
                                        <div class="ibox-title">
                                            <h5>Thông tin chung</h5>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row">
                                                <div class="col-lg-12 mb10">
                                                    <input type="hidden" name="agency_id" value="{{ $agency->id }}">
                                                    <div class="form-row customerWrapper">
                                                        <label for="" class="control-label text-left">Nhóm khách hàng <span class="text-danger">(*)</span></label>
                                                        <select name="customer_catalogue_id" class="input-text setupSelect2">
                                                            <option value="0">[Chọn Nhóm Khách Hàng]</option>
                                                            @foreach($customerCatalogues as $key => $item)
                                                            <option {{ 
                                                                $item->id == old('customer_catalogue_id', (isset($customer->customer_catalogue_id)) ? $customer->customer_catalogue_id : '') ? 'selected' : '' 
                                                                }}  value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-row customerWrapper">
                                                        <label for="" class="control-label text-left">Nguồn khách <span class="text-danger">(*)</span></label>
                                                        <select name="source_id" class="input-text setupSelect2">
                                                            <option value="0">[Chọn Nguồn khách]</option>
                                                            @foreach($sources as $key => $val)
                                                            <option {{ 
                                                                $val->id == old('source_id', (isset($customer->source_id)) ? $customer->source_id : '') ? 'selected' : '' 
                                                                }}  value="{{ $val->id }}">{{ $val->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="ibox">
                                        <div class="ibox-title">
                                            <h5>Thông tin khách hàng</h5>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row mb15">
                                                <div class="col-lg-5">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Email <span class="text-danger">(*)</span></label>
                                                        <input 
                                                            type="text"
                                                            name="email"
                                                            value="{{ old('email' ) }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Mã khách hàng</label>
                                                        <div class="code">
                                                            <input 
                                                                type="text"
                                                                name="code"
                                                                value="{{ old('code', ($customer->code) ?? time() ) }}"
                                                                class="input-text"
                                                                placeholder=""
                                                                autocomplete="off"
                                                                readonly
                                                            >
                                                            <input type="checkbox">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Họ Tên <span class="text-danger">(*)</span></label>
                                                        <input 
                                                            type="text"
                                                            name="name"
                                                            value="{{ old('name') }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb15">
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Mật khẩu <span class="text-danger">(*)</span></label>
                                                        <input 
                                                            type="password"
                                                            name="password"
                                                            value=""
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Nhập lại mật khẩu <span class="text-danger">(*)</span></label>
                                                        <input 
                                                            type="password"
                                                            name="re_password"
                                                            value=""
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb15">
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Ảnh đại diện </label>
                                                        <input 
                                                            type="text"
                                                            name="image"
                                                            value="{{ old('image') }}"
                                                            class="input-text upload-image"
                                                            placeholder=""
                                                            autocomplete="off"
                                                            data-upload="Images"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Ngày sinh </label>
                                                        <input 
                                                            type="date"
                                                            name="birthday"
                                                            value="{{ old('birthday', (isset($customer->birthday)) ? date('Y-m-d', strtotime($customer->birthday)) : '') }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb15">
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Thành Phố</label>
                                                        <select name="province_id" class="input-text setupSelect2 province location" data-target="districts">
                                                            <option value="0">[Chọn Thành Phố]</option>
                                                            @if(isset($provinces))
                                                                @foreach($provinces as $province)
                                                                <option @if(old('province_id') == $province->code) selected @endif value="{{ $province->code }}">{{ $province->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Quận/Huyện </label>
                                                        <select name="district_id" class="input-text districts setupSelect2 location" data-target="wards">
                                                            <option value="0">[Chọn Quận/Huyện]</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb15">
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Phường/Xã </label>
                                                        <select name="ward_id" class="input-text setupSelect2 wards">
                                                            <option value="0">[Chọn Phường/Xã]</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Địa chỉ </label>
                                                        <input 
                                                            type="text"
                                                            name="address"
                                                            value="{{ old('address') }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb15">
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Số điện thoại</label>
                                                        <input 
                                                            type="text"
                                                            name="phone"
                                                            value="{{ old('phone') }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Ghi chú</label>
                                                        <input 
                                                            type="text"
                                                            name="description"
                                                            value="{{ old('description') }}"
                                                            class="input-text"
                                                            placeholder=""
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mb15">
                            <button class="btn-create" type="submit" name="send" value="send">Thêm mới khách hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection