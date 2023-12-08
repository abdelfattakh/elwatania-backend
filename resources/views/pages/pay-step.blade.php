@extends('layout.app')
@section('body')
    <h1 class="h1-step">{{__('web.payment_step')}}</h1>
    <form method="post" action="{{route('order.create',['address_id'=>$address->id])}}">
        @csrf
        <div class="pay">
            @include('alerts.error')
            <div class="container">
                <div class="pay-data" data-aos="fade-up">
                    <h1> {{__('web.order_details')}} ( {{count($cart)}} )</h1>
                    @foreach($cart as $item)

                        <div class="item1">
                            <img src="{{asset('frontend/images/22.svg')}}" alt=""/>
                            <div class="pay-info">
                                <h2>
                                    @if(app()->getLocale() =='en')
                                        {{data_get($item,'associatedModel.name.en')}}
                                    @elseif(app()->getLocale() =='ar')
                                        {{data_get($item,'associatedModel.name.ar')}}
                                    @endif

                                </h2>
                                <ul>
                                    <li class="li-f">
                                        <h3 class="sm-f">{{__('web.quantity')}}</h3>
                                        <h4>{{data_get($item,'quantity')}}</h4>
                                    </li>
                                    <li>
                                        <h3>{{__('web.price')}}</h3>
                                        <h4>{{data_get($item,'price')*data_get($item,'quantity')}} {{ __('web.egp') }}</h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                    <div class="item2">
                        <ul>
                            <li>
                                <h3>{{__('web.price')}}</h3>
                                <h4>{{$sub_total}} {{ __('web.egp') }}</h4>
                            </li>
                            <li>
                                <h3>{{__('web.delivery_fees')}}</h3>
                                <h4>{{$address->city->delivery_fees}} {{ __('web.egp') }}</h4>
                            </li>

                            <li>
                                <h3>{{__('web.tax')}}</h3>
                                <h4>{{$tax}} {{ __('web.egp') }}</h4>
                            </li>
                            <li>
                                <h3>{{__('web.disount_value')}}</h3>
                                <h4 class="p-sale">{{$coupon_value}} {{ __('web.egp') }}</h4>
                            </li>
                            <li>
                                <h3>{{__('web.total')}}</h3>
                                <h4>{{$total}} {{ __('web.egp') }}</h4>
                            </li>
                        </ul>
                    </div>

                    <input type="submit" class="order-now" id="order-now" value="{{__('web.order_now')}}">
                </div>

                <div class="pay-user" data-aos="fade-down">
                    <h2>{{__('WEB.shipping_address')}}</h2>
                    <div class="item3">
                        <div class="user-name">
                            <h2>{{$address->name.' '.$address->family_name}}</h2>
                            <a class="change"
                               href="{{ route('updateaddress',['id'=>$address->id]) }}">{{__('web.change')}}</a>
                        </div>
                        <h2 class="ads">
                            {{$address->street_name}}
                        </h2>
                        <div class="ph-p">
                            <h2 class="phone">{{$address->phone}}</h2>
                            <h3 class="p-sale">{{$address->area->shipping_time}}</h3>
                        </div>
                    </div>
                    <h2>{{__('web.payment_method')}}</h2>
                    <div class="item4">
                        <div class="pay-card">

                            <div class="form-check">
                                <div class="choose-pay">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="flexRadioDefault2"required />
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        {{__('web.credit')}}
                                    </label>
                                </div>
                                <img src="{{asset('frontend/images/credit_card.svg')}}" alt=""/>
                            </div>

                            <div class="pay-info1" id="pay-info">
                            </div>
                        </div>
                        <span></span>

                        <div class="pay-cash">
                            <div class="form-check">
                                <input class="form-check-input cash" type="radio" name="payment_method" required
                                       value="1" id="flexRadioDefault2"/>
                                <label class="form-check-label" for="flexRadioDefault2" >
                                    {{__('web.cash')}}
                                </label>
                            </div>
                            <img src="{{ asset('frontend/images/cash.svg') }}" alt=""/>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <!--end pay-steps-->
    </form>
@endsection
@push('scripts')
    <script>
        const card = document.getElementById("flexRadioDefault2");
        const info_card = document.getElementById("pay-info");
        window.onchange = () => {
            if (card.checked) {
                info_card.style.display = "block"
            } else {
                info_card.style.display = "none"
            }
        }
        $('form').submit(function(){
            $('input[type=submit]', this).attr('disabled', 'disabled');
        });
    </script>
@endpush
