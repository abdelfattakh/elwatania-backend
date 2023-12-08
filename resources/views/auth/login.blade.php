@extends('layout.app', ['current_page' => 'login', 'title' => __('web.login')])

@section('body')
    <!--start login page-->
    <div class="page-login">
        <div class="container">
            <div class="form-login">
                <h1>{{ __('web.login') }}</h1>
                <div class="login-with">
                    <ul>
                        <li>

                            <a href="{{route('loginWithFacbook',['provider_name'=>'google'])}}">
                                <img src="{{ asset('frontend/images/google_btn.svg') }}" alt="google-email">
                            </a>
                        </li>
                        <li>

                            <a href="{{route('loginWithFacbook',['provider_name'=>'facebook'])}}">
                                <img src="{{ asset('frontend/images/facbook_icon.png') }}" alt="facebook-email">
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="or">
                    <span></span>
                    <h3>{{ __('web.or') }}</h3>
                </div>

                @include('alerts.error')
                @include('alerts.success')


                <form action="{{route('login.submit')}}" method="post">
                    @csrf
                    <div class="login-email">
                        <h2>{{ __('web.email') }}</h2>
                        <input type="email"
                               name="email"
                               placeholder="{{ __('web.write_email') }}"
                               id="email"
                               value="{{ old('email') }}">
                        @include('alerts.inline-error', ['field' => 'email'])
                    </div>
                    <div class="login-pass">
                        <h2>{{ __('web.password') }}</h2>
                        <input type="password" name="password" placeholder="{{ __('web.write_password') }}" id="name">
                        @include('alerts.inline-error', ['field' => 'password'])
                    </div>

                    <div class="forget">
                        <ul>

                            <li>
                                <a href="{{ route('auth.sendResetCode') }}">{{ __('web.forget_password') }}</a>
                            </li>
                        </ul>
                    </div>

                    <input type="submit" value={{ __('web.login') }}>
                </form>

                <div class="forget">
                    <ul>
                        <li class="forget-pa">
                            <a href="{{ route('register') }}">{{ __('web.register') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="login-logo">
                <img src="{{ asset('frontend/images/logo2.svg') }}" class="bigimage" alt="logo alwatania">
                <img src="{{ asset('frontend/images/logo.svg') }}" class="imgsmall" alt="logo alwatania">
            </div>
        </div>
    </div>
    <!--end login page-->
@endsection
