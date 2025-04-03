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
                    <h2 class="heading-2">Xóa thông tin công trình</h2>
                    <div class="description">
                        Quản lý thông tin chi tiết danh sách các công trình tại Omega Deco
                    </div>
                </div>
                <div class="panel-profile">
                    @include('backend.dashboard.component.formError')
                    <form action="{{ route('agency.construction.destroy', $construct->id) }}" method="post" class="box">
                        @csrf
                        @method('DELETE')
                        <div class="panel-wrapper">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="panel-head">
                                        <div class="panel-title">Thông tin chung</div>
                                        <div class="panel-description">
                                            <p>Bạn đang muốn xóa công trình : {{ $construct->name }}</p>
                                            <p>Lưu ý: Không thể khôi phục thông tin  sau khi xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="ibox">
                                        <div class="ibox-content">
                                            <div class="row mb15">
                                                <div class="col-lg-12">
                                                    <div class="form-row">
                                                        <label for="" class="control-label text-left">Tên công trình</label>
                                                        <input 
                                                            type="text"
                                                            name="name"
                                                            value="{{ old('name', ($construct->name) ?? '' ) }}"
                                                            class="form-control"
                                                            placeholder=""
                                                            autocomplete="off"
                                                            readonly
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
                            <button class="btn btn-danger" type="submit" name="send" value="send">Xóa dữ liệu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
