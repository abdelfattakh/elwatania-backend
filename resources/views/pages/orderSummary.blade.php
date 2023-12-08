<form action="{{route('home')}}" method="post">
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
