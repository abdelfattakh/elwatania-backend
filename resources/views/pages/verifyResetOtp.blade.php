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

                <form action="{{route('auth.verifyResetCode.submit')}}" method="post">
                    @csrf
                    <div class="login-name">
                        <h2> {{__('web.otp_code')}} </h2>
                        <input type="text" name="code" placeholder="{{__('web.write_otp_code')}}" id="code">
                        @include('alerts.inline-error', ['field' => 'error'])
                    </div>
                    <input type="submit" value="{{__('web.verify')}}">
                </form>
                <a href="{{route('login')}}" class="cancle">{{__('web.cancel')}}</a>
            </div>
            <div class="login-logo">
                <img src="{{ asset('frontend/images/logo2.svg') }}" class="bigimage" alt="logo alwatania">
                <img src="{{ asset('frontend/images/logo.svg') }}" class="imgsmall" alt="logo alwatania">
            </div>
        </div>
    </div>
    <!--end login page-->
@endsection
