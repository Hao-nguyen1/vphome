@extends('frontend.homepage.layout')
@section('content')
    <div class="register-container">
        <div class="uk-container uk-container-center">
            <div class="register-page">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-2-3">
                        <div class="register-wrapper">
                        </div>
                    </div>
                    <div class="uk-width-large-1-3">
                        <div class="register-form">
                            <form action="{{ route('customer.reg')}}" method="post">
                                @csrf
                                    <div class="form-heading">Đăng ký</div>
                                    <div class="form-row">
                                        <input 
                                            type="text" 
                                            class="input-text" 
                                            name="name"
                                            value="{{ old('name') }}"  
                                            placeholder="Họ tên"
                                        >
                                        @if($errors->has('name'))
                                            <span class="error-message">* {{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
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
                                    <div class="form-row">
                                        <input 
                                            type="password" 
                                            name="password"
                                            class="input-text" 
                                            placeholder="Mật khẩu"
                                            autocomplete="off"
                                        >
                                        @if($errors->has('password'))
                                            <span class="error-message">* {{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-row">
                                        <input 
                                            type="password" 
                                            class="input-text" 
                                            name="re_password"
                                            placeholder="Nhập lại mật khẩu"
                                            autocomplete="off"
                                        >
                                        @if($errors->has('re_password'))
                                            <span class="error-message">* {{ $errors->first('re_password') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-row">
                                        <input 
                                            type="text" 
                                            class="input-text" 
                                            name="phone"
                                            value="{{ old('phone') }}" 
                                            placeholder="Số điện thoại"
                                        >
                                    </div>
                                    <div class="form-row">
                                        <input 
                                            type="text" 
                                            class="input-text" 
                                            name="address"
                                            value="{{ old('address') }}" 
                                            placeholder="Địa chỉ"
                                        >
                                        <input type="hidden" name="customer_catalogue_id" value="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary block full-width m-b">Đăng ký</button>  
                            </form>
                            <p class="m-t mt5">
                                <small>{{ $system['homepage_brand'] }} 2023</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
