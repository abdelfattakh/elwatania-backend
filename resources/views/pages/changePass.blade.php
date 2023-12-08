@extends('layout.app')

@section('body')

    <div class="pass-change">
        <div class="container">
            <div class="pass">
                <h2>{{__('web.change_password')}}</h2>
                <form action="{{route('auth.change_password')}}" method="post">
                    @csrf
                    <div class="login-name">
                        <h3> {{__('web.current_password')}}</h3>
                        <input type="password" name="current_password" placeholder="{{__('web.write_current_password')}}"
                               id="current_password">
                    </div>
                    <div class="login-name">
                        <h3> {{__('web.new_password')}}</h3>
                        <input type="password" name="password" placeholder="  {{__('web.write_new_password')}}"
                               id="password">
                    </div>
                    <div class="login-email">
                        <h3>  {{__('web.confirm_new_password')}}</h3>
                        <input type="password" name="password_confirmation"
                               placeholder=" {{__('web.confirm_new_password')}}  " id="password_confirmation">
                    </div>
                    <button type="submit">{{__('web.change')}}</button>
                    <a href="{{route('updateProfile')}}">{{__('web.cancel')}}</a>
                </form>
            </div>
        </div>
    </div>
@endsection
