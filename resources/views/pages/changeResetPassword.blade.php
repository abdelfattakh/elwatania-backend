@extends('layout.app')
@section('body')
    <!--start login page-->
    <div class="page-login">
        <div class="container">
            <div class="form-login getcount">
                <div class="headform">
                    <a href="{{route('login')}}"><img src="{{asset('frontend/images/ic_clear_24px.svg')}}" style="width: 18px;" alt=""></a>
                    <h1>{{__('web.get_account')}}</h1>
                </div>
                @include('alerts.error')
                <form action="{{route('auth.changePassword')}}" method="post">
                    @csrf
                    <div class="login-pass">
                        <h2>  {{__('web.new_password')}} </h2>
                        <input type="password" name="password" placeholder="{{__('web.new_password')}}" id="password">
                        @include('alerts.inline-error', ['field' => 'password'])

                    </div>
                    <div class="login-pass">
                        <h2>{{__('web.confirm_new_password')}} </h2>
                        <input type="password" name="password_confirmation" placeholder="{{__('web.write_confirm_new_password')}}" id="passwordConfirmation">
                        @include('alerts.inline-error', ['field' => 'password_confirmation'])

                    </div>
                    <input type="submit" value="{{__('web.change')}}">
                </form>
                <a href="{{route('login')}}" class="cancle">{{__('web.cancel')}}</a>
            </div>
            <div class="login-logo">
                <img src="{{asset('frontend/images/logo.svg')}}" class="bigimage" alt="logo alwatania">
                <img src="{{asset('frontend/images/Group 6.svg')}}" class="imgsmall" alt="logo alwatania">
            </div>
        </div>
    </div>
    <!--end login page-->
@endsection
