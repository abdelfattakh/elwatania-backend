@extends('layout.app')
@section('body')
    <!--start login page-->
    <div class="page-login">
        <div class="container">
            <form method="post" action="{{route('auth.reset_password')}}">
                @csrf
                <div class="form-login getcount">
                    <div class="headform">
                        <a href="#"><img src="{{asset('frontend/images/wrong_red.svg')}}" style="width: 18px;"
                                         alt=""></a>
                        <h1>{{__('web.get_account')}}</h1>
                    </div>


                    <div class="login-email">
                        <h2>{{__('web.email')}}</h2>
                        <input type="email" name="email" placeholder="{{__('web.write_email')}}" id="">
                        @include('alerts.inline-error', ['field' => 'email'])
                    </div>


                    <input type="submit" value="{{__('web.send_code')}}">
                    <a href="{{route('login')}}" class="cancle">{{__('web.cancel')}}</a>
                </div>
            </form>
            <div class="login-logo">
                <img src="{{ asset('frontend/images/logo2.svg') }}" class="bigimage" alt="logo alwatania">
                <img src="{{asset('frontend/images/logo.svg')}}" class="imgsmall" alt="logo alwatania">
            </div>
        </div>
    </div>
    <!--end login page-->
@endsection
