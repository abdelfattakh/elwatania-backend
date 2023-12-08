@extends('layout.app', ['current_page' => 'register', 'title' => __('web.register')])

@section('body')
    <!--start register page-->
    <div class="page-login">
        <div class="container">
            <div class="form-login">
                <h1>{{ __('web.register') }}</h1>

                @include('alerts.error')
                @include('alerts.success')

                <form action="{{ route('register.submit') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="login-name">
                        <h2>{{ __('web.first_name') }}</h2>
                        <input type="text" id="first_name" name="first_name"
                               required
                               placeholder="{{ __('web.write_first_name') }}"
                               value="{{ old('first_name') }}">
                        @include('alerts.inline-error', ['name' => 'first_name'])
                    </div>

                    <div class="login-name">
                        <h2>{{ __('web.last_name') }}</h2>
                        <input type="text" id="last_name" name="last_name"
                               required
                               placeholder="{{ __('web.write_last_name') }}"
                               value="{{ old('last_name') }}">
                        @include('alerts.inline-error', ['name' => 'last_name'])
                    </div>

                    <div class="login-email">
                        <h2> {{ __('web.email') }}</h2>
                        <input type="email" id="email" name="email"
                               required
                               placeholder="{{ __('web.write_email') }}"
                               value="{{ old('email') }}">
                        @include('alerts.inline-error', ['name' => 'email'])
                    </div>

                    <div class="login-pass">
                        <h2>{{ __('web.password') }}</h2>
                        <input type="password" id="password" name="password"
                               required
                               placeholder="{{ __('web.write_password') }}">
                        @include('alerts.inline-error', ['name' => 'password'])
                    </div>

                    <div class="chek">
                        <label class="form-check-label" for="flexCheckChecked">
                            <h3>{{ __('web.terms_and_conditions') }}</h3>
                        </label>
                        <input type="checkbox" name="terms" required id="flexCheckChecked">
                        @include('alerts.inline-error', ['name' => 'terms'])
                    </div>

                    <input type="submit" value={{ __('web.register_form') }}>
                </form>

                <div class="forget">
                    <ul>
                        <li class="forget-pa"><a href="{{ route('login') }}">{{ __('web.login') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="login-logo">
                <img src="{{ asset('frontend/images/logo2.svg') }}" class="bigimage" alt="logo alwatania">
                <img src="{{ asset('frontend/images/logo.svg') }}" class="imgsmall" alt="logo alwatania">
            </div>
        </div>
    </div>
    <!--end register page-->
@endsection
