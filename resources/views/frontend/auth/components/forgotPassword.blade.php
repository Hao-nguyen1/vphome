@extends('frontend.homepage.layout')
@section('content')
    <div class="forgotpassword-container">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-2-3"></div>
                <div class="uk-width-large-1-3">
                    <div class="register-form">
                        <form action="{{ route($route) }}" method="get">
                            @csrf
                                <div class="form-heading">Quên mật khẩu</div>
                                <p>Nhập địa chỉ email của bạn và mật khẩu của bạn sẽ được đặt lại và gửi qua email cho bạn.</p>
                                <div class="form-row">
                                    <input 
                                        type="text" 
                                        class="input-text" 
                                        name="email"
                                        value="{{ old('email') }}" 
                                        placeholder="Email"
                                    >
                                    @if($errors->has('email'))
                                        <span class="error-message">* {{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <button type="submit" class="btn block full-width m-b">Gửi mật khẩu mới</button>  
                        </form>
                        <p class="m-t mt5">
                            <small>{{ $system['homepage_brand'] }} © 2023</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
