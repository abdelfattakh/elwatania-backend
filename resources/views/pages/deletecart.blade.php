@extends('layout.app')
@section('body')

    <div class="basket">
        <div class="container">
            <h1>السلة (2)</h1>
            <div class="my-basket">
                <form action="{{route('deleteItem')}}" method="post">
                    @csrf
                    <div class="all-basket">
                        @foreach($cart as $item)

                            <div class="all-item" style="margin: 6px auto">

                                <div class="item">
                                    <img src="{{asset('frontend/images/2b.svg')}}" alt="">
                                    <div class="item-about">
                                        <h2>{{$item['name']}}</h2>
                                        <div class="price-num">
                                            <div class="add-del">
                                                <button class="tv-del" type="button" id="tv-del">-</button>
                                                <input type="number" class="num-tv" id="num-tv" readonly value=1>
                                                <button class="tv-add" type="button" id="tv-add">+
                                                </button>

                                            </div>
                                            <h3 class="price">{{$item['associatedModel']['final_price'] *  $item['quantity']}}
                                                {{ __('web.egp') }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="end-basket">
                                    <button class="addto-fi" type="submit">
                                        <img src="{{asset('frontend/images/heart_off.svg')}}" alt="">
                                        <h3>  {{__('web.add_to_favourite')}}</h3>
                                    </button>
                                    <button class="delet-item" type="submit">
                                        <img src="{{asset('frontend/images/bin_red.svg')}}" alt="">
                                        <h4>{{__('web.delete_item')}}</h4>
                                    </button>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </form>
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
    </div>
    <!-- end basket-->

@endsection
@push('scripts')
    <script>
        var del = document.getElementById("tv-del");
        const add = document.getElementById(`tv-add`);
        const val = document.getElementById("num-tv").value;
        console.log(document.getElementById("num-tv"))
        var num = 1;

        del.addEventListener("click", function () {
            if (num <= 1) {
                num = 1;

            } else {
                num--;

            }
            document.getElementById("num-tv").value = num
        });

        add.addEventListener("click", function () {
            num += 1;
            document.getElementById("num-tv").value = num
        });
        val = num
    </script>
    <script>

    </script>
@endpush
