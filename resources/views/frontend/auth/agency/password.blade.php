@extends('frontend.homepage.layout')
@section('content')
    <div class="profile-container pt20 pb20">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-4">
                    @include('frontend.auth.agency.components.sidebar')
                </div>
                <div class="uk-width-large-2-4">
                    <div class="panel-profile">
                        <div class="panel-head">
                            <h2 class="heading-2"><span>Thay đổi mật khẩu</span></h2>
                            <div class="description">
                                Quản lý thông tin hồ sơ để bảo mật tài khoản, nhập đầy đủ thông tin dể tiến hành thay đổi mật khẩu
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('backend/dashboard/component/formError')
                            <form action="{{ route('agency.password.recovery') }}" method="post" class="uk-form uk-form-horizontal login-form profile-form">
                                @csrf
                                
                                <div class="uk-form-row form-row">
                                    <label class="uk-form-label" for="form-h-it">Mật khẩu cũ</label>
                                    <div class="uk-form-controls">
                                        <input 
                                            type="password" 
                                            class="input-text"
                                            placeholder="Nhập vào mật khẩu cũ"
                                            name="password"
                                            value=""
                                        >
                                    </div>
                                </div>
                                <div class="uk-form-row form-row">
                                    <label class="uk-form-label" for="form-h-it">Mật khẩu mới</label>
                                    <div class="uk-form-controls">
                                        <input 
                                            type="password" 
                                            class="input-text"
                                            placeholder="Nhập vào mật khẩu mới"
                                            name="new_password"
                                            value=""
                                        >
                                    </div>
                                </div>
                                <div class="uk-form-row form-row">
                                    <label class="uk-form-label" for="form-h-it">Nhập lại mật khẩu mới</label>
                                    <div class="uk-form-controls">
                                        <input 
                                            type="password" 
                                            class="input-text"
                                            placeholder="Nhập lại mật khẩu mới"
                                            name="re_new_password"
                                            value=""
                                        >
                                    </div>
                                </div>
                               
                                <button type="submit" name="send" value="create">Đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



