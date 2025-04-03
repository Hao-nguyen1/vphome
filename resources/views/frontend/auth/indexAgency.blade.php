@extends('frontend.homepage.layout')
@section('content')
    <div class="login-container">
        <div class="uk-container uk-container-center">
            <div class="login-page">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-2-3">
                        <div class="login-wrapper">                           
                        </div>
                    </div>
                    <div class="uk-width-large-1-3">
                        <div class="login-form">
                            <form action="{{ route('fe.auth.agency.dologin') }}" class="uk-form form">
                                @csrf
                                <div class="form-heading">Đăng nhập đại lý</div>
                                <div class="form-row">
                                    <input 
                                        type="text"
                                        name="email"
                                        value=""
                                        placeholder="Email đăng nhập"
                                        class="input-text"
                                    >
                                </div>
                                <div class="form-row">
                                    <input 
                                        type="password"
                                        name="password"
                                        value=""
                                        placeholder="Mật khẩu"
                                        class="input-text"
                                    >
                                </div>
                                <button type="submit" value="login" name="login">Đăng nhập đại lý</button>
                                <div class="form-row forgot-password">
                                    <div class="uk-flex uk-flex-middle">
                                        <a href="{{ route('forgot.agency.password') }}">Quên mật khẩu</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

