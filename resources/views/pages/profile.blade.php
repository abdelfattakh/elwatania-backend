@extends('layout.app')

@section('body')

    <div class="main">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'profile'])
            <div class="main-about">
                <form method="post" action="{{route('updateProfile.submit')}}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">{{__('web.first_name')}} </label>
                        <input name="first_name" value="{{auth()->user()->first_name}}" type="text"
                               placeholder=" {{__('web.write_first_name')}}" class="form-control" id="first_name">
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label"> {{__('web.last_name')}}</label>
                        <input name="last_name" value="{{auth()->user()->last_name}}" type="text"
                               placeholder=" {{__('web.write_last_name')}}" class="form-control" id="last_name">
                    </div>

                    <div class="col-md-12 information">
                        <h2> {{__('web.email')}}</h2>
                        <ul>
                            <li>{{auth()->user()->email}}</li>
                            <li class="change-info"><a
                                    href="{{route('auth.change_email')}}">{{__('web.change_email')}} </a></li>
                        </ul>
                    </div>
                    <div class="col-md-12 information">
                        <h2>{{__('web.password')}}  </h2>
                        <ul>
                            <li>**************</li>
                            <li class="change-info"><a href="{{route('changePass')}}">{{__('web.change_password')}}</a>
                            </li>
                        </ul>
                    </div>
                    <button type="submit" class="col-md-12 col-12"
                            style="margin: 100px 0px 20px">{{__('web.save')}}</button>
                </form>
            </div>


        </div>
    </div>

@endsection
