<div class="container">
    <h1> {{count($cart)}}</h1>
    <div class="my-basket">

        <div class="all-basket">
            @foreach($cart as $item)

                <div class="all-item" style="margin: 6px auto">

                    <div class="item">
                        <img src="{{asset('frontend/images/2b.svg')}}" alt="">
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
                            @auth
                                <img id="addfavourites" onclick="addFavourite({{$item['id'] }},this)"
                                     @if(\Maize\Markable\Models\Favorite::has(new \App\Models\Product($item['associatedModel']), auth()->user()))src="{{asset('frontend/images/heart_on.svg')}}"
                                     @else src="{{ asset('frontend/images/heart_red_dark_off.svg') }}"
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


        <form action="#" method="post">
            @csrf
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
                            <input type="text" name="" placeholder=" {{__('web.add_coupon_code')}}" id="">
                            <img class="yes" src="{{asset('frontend/images/checkmark.svg')}}" alt="">
                            <img class="no" src="{{asset('frontend/images/wrong_mark.svg')}}" alt="">


                        </div>
                        <button class="checkout" type="submit">{{__('web.apply')}}</button>
                    </li>
                    <li><span class="line"></span></li>
                    <li class="total">
                        <h3>  {{__('web.total')}}</h3>
                        <h4>{{$total}} {{ __('web.egp') }}</h4>
                    </li>
                </ul>
                <a href="#">
                    <button type="submit" class="coun-pay">{{__('web.proceed_to_buy')}}</button>
                </a>
            </div>
        </form>
    </div>
</div>
<!-- end basket-->

