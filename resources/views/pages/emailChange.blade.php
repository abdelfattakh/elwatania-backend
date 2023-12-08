@extends('layout.app')
@section('body')

<div class="pass-change ">

    <div class="container">
        <div class="pass">
            @include('alerts.error')
            @include('alerts.success')
            <h2 style="text-align: center;">{{__('web.change_email')}}</h2>
            <form action="{{route('auth.changeEmailSubmit')}}" method="post">
                @csrf
                <div class="login-name">
                    <h3> {{__('web.email')}}</h3>
                    <input type="email" name="email" placeholder=" {{__('web.write_email')}}" id="">
                </div>
                <button>{{__('web.send')}}</button>
                <a href="{{route('updateProfile')}}">{{__('web.cancel')}}</a>
            </form>
        </div>
    </div>
</div>
@endsection
