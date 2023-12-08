@extends('layout.app')
@section('body')
        @fragment('content')
        <div id="parent" class="basket">
        <div class="container">
            @if(!filled($cart))
            <div class="no-item">
                <img src="{{asset('frontend/images/empty-cart.png')}}" style="max-width: 301px" alt="">
                <p style="font-size:40px;" >{{__('web.empty_cart')}}</p>
                <a href="{{route('home')}}" class="btn-shopping" style="display: flex; align-items: center; justify-content: center;">{{ __('web.browse_offers') }}</a>
            </div>
            @endif
            @if(filled($cart))
            <h1> {{count($cart)}}</h1>
            <div class="my-basket">
                <div class="all-basket">

                    @foreach($cart as $item)
                        @php
                            /** @var \App\Models\Product $product */
                            $isFav = auth('web')->check() && \Maize\Markable\Models\Favorite::has(new \App\Models\Product($item['associatedModel']), auth('web')->user());
                        @endphp
                        <div class="all-item" style="margin: 6px auto">

                            <div class="item">
                                <a href="{{route('products.show',['id'=>$item['associatedModel']['id']])}}">
                                    <img style="width: 129px;height: 103px"
                                         src="{{(new \App\Models\Product($item['associatedModel']))->getFirstMediaUrl('cover_image')}}"
                                         onerror="this.src='{{ asset('icon.svg') }}'"
                                         alt="">
                                </a>
                                <div class="item-about">
                                    <h2>{{$item['name']}}</h2>
                                    <div class="price-num">

                                        <div class="add-del">

                                            <button onclick="updateCart({{$item['id']}},this)" class="tv-del"
                                                    name="del" type="button" value="-">-
                                            </button>
                                            <input type="number" name="num-tv" class="num-tv" id="num-{{$item['id']}}"
                                                   value="{{$item['quantity']}}">
                                            <button onclick="updateCart({{$item['id']}},this)" class="tv-add"
                                                    name="add" type="button" value="+">+
                                            </button>

                                        </div>
                                        <h3 class="price"
                                            id="final_price">{{$item['associatedModel']['final_price'] *  $item['quantity']}}
                                            {{ __('web.egp') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="end-basket">
                                <button class="addto-fi" type="submit">
                                    @auth('web')
                                        <img id="addfavourites" onclick="addFavourite({{$item['id'] }})"
                                             class="product-fav-{{ $item['id'] }}"
                                             @if(\Maize\Markable\Models\Favorite::has(new \App\Models\Product($item['associatedModel']), auth()->user()))
                                                 src="{{asset('frontend/images/heart_on.svg')}}"
                                             @else
                                                 src="{{ asset('frontend/images/heart_red_dark_off.svg') }}"
                                             @endif
                                             style="width:  22px" alt=""/>
                                    @endauth
                                    @guest
                                        <img id="addfavourites" onclick="window.location.href='{{route('login')}}'"
                                             src="{{ asset('frontend/images/heart_red_dark_off.svg') }}"
                                             style="width:  22px" alt=""/>
                                    @endguest
                                    <h3>  {{__('web.add_to_favourite')}}</h3>
                                </button>
                                <form action="{{route('deleteItem')}}" method="post">
                                    @csrf
                                    <button class="delet-item" type="submit">
                                        <input type="hidden" name="product_id" value="{{$item['id']}}">
                                        <img src="{{asset('frontend/images/bin_red.svg')}}" alt="">
                                        <h4>{{__('web.delete_item')}}</h4>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="summer-basket">
                    <h2>{{__('web.order_summary')}}</h2>
                    <ul>
                        <li>
                            <h3> {{__('web.sub_total')}}</h3>
                            <h4>{{$sub_total}}</h4>
                        </li>
                        <li>
                            <h3>{{__('web.delivery_fees')}}</h3>
                            <h4>{{__('web.will_be_calculated')}}</h4>
                        </li>

                        <li class="code-res">
                            <h3> {{__('web.add_coupon_code')}}</h3>
                            <div class="code">
                                @if(isset($coupon_value))
                                <input type="text" name="coupon_code" placeholder=" {{__('web.add_coupon_code')}}"
                                       id="coupon_code">
                                    <img id="yes" class="yes" src="{{asset('frontend/images/checkmark.svg')}}" alt="">
                                    <img id="no" class="no" src="{{asset('frontend/images/wrong_mark.svg')}}" alt="">
                                @else
                                    <input type="text" name="coupon_code" placeholder=" {{__('web.add_coupon_code')}}"
                                           id="coupon_code">
                                    <img id="no" class="no" src="{{asset('frontend/images/wrong_mark.svg')}}" alt="">
                                @endif
                            </div>
                            <button class="checkout" id="applybutton"
                                    onclick="AddCoupon(this)">Apply</button>
                            <p style="color:#AE0028; font-size: 20px" id="result"></p>
                        </li>

                        <li><span class="line"></span></li>
                        <li class="total">
                            <h3>  {{__('web.total')}}</h3>
                            <h4>{{ round(\Cart::session(auth()->user()->id)->getTotal(), 2) }} {{ __('web.egp') }}</h4>
                        </li>
                    </ul>
                    <form method="get" action="{{route('address')}}">
                        @csrf
                        <button type="submit" class="coun-pay">{{__('web.proceed_to_buy')}}</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
        </div>
        @endfragment

    <!-- end basket-->


@endsection
@push('scripts')
    @include('scripts.getQuantityValue');
    @include('scripts.updateCart');
    @include('scripts.couponValidation');
@endpush
