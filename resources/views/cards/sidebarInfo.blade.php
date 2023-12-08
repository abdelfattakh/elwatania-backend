@php
    $page ??= '';
@endphp

<div class="main-link">
    <div class="user">
        <div class="user-info">
            <h1 class="name-user">{{auth()?->user()->full_name}}</h1>
            <h2 class="email-user">{{auth()?->user()->email}} </h2>
        </div>
        <a href="{{route('updateProfile')}}" class="user-update">
            <img src="{{asset('frontend/images/edit.svg')}}" alt="">
        </a>
    </div>

    <ul>
        <li @class(['link-activ' => $page == 'main'])>
            <a href="{{route('profile')}}">
                @if($page =='main') <img src="{{asset('frontend/images/main_on.png')}}" alt="">@else<img  style="width: 11.2px; height: 16px" src="{{asset('frontend/images/home_off.svg')}}" alt=""> @endif
                <h2>{{__('web.home')}}</h2>
            </a>
        </li>

        <li @class(['link-activ' => $page == 'orders'])>
            <a href="{{route('request')}}">
                @if($page =='orders') <img src="{{asset('frontend/images/order_on.png')}}" alt="">@else<img  style="width: 11.2px; height: 16px" src="{{asset('frontend/images/order_off.png')}}" alt=""> @endif
                <h2>{{__('web.my_orders')}}</h2>
            </a>
        </li>

        <li @class(['link-activ' => $page == 'favourites'])>
            <a href="{{route('perfect')}}">
                @if($page =='favourites') <img src="{{asset('frontend/images/heart_red_dark_off.svg')}}" alt="">@else<img  style="width: 11.2px; height: 16px" src="{{asset('frontend/images/heart_gray_off.svg')}}" alt=""> @endif

                <h2>{{__('web.favourites')}}</h2>
            </a>
        </li>

        <li @class(['link-activ' => $page == 'address'])>
            <a href="{{route('address')}}">
             @if($page =='address') <img src="{{asset('frontend/images/location_red.svg')}}" alt="">@else<img  style="width: 11.2px; height: 16px" src="{{asset('frontend/images/Icon material-location-on.png')}}" alt=""> @endif
                <h2> {{__('web.addresses')}}</h2>
            </a>
        </li>
    </ul>
    <a href="{{route('logout')}}" class="logout">{{__('web.log_out')}}</a>
</div>
