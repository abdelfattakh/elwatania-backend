@extends('layout.app')

@section('body')

    <div class="main request">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'orders'])
            @if(!filled($current_orders)&&!filled($previous_orders))
                <div class="main-about">
                    <h1>{{__('web.hello').' '.auth()->user()->full_name}}</h1>
                    <div class="part">
                        <h2>{{__('web.latest_order')}}</h2>
                        <p>{{__('web.go_shopping_recommendation')}}</p>
                        <a href="{{route('home')}}">
                            <button> {{__('web.go_shopping')}}</button></a>
                    </div>
                </div>
        </div>
            @endif
            <div class="req-about">
                @foreach($current_orders as $current_order)
                    <h1> {{__('web.inProgress_orders')}}</h1>
                @foreach($current_order->orderItems as $item)

                        <div class="now">
                            <h1 style="color: #ae0028; font-weight: bold"> {{__('web.order_code').' '.$current_order->order_code}}</h1>

                            <div class="now-req">

                                <h2 class="status"> {{__('web.status_'.\Illuminate\Support\Str::lower($current_order->status))}}</h2>

                                <a href="{{route('products.show',['id'=>$item->id])}}">
                                       <img src="{{\App\Models\Product::find($item->id)->getFirstMediaUrl('cover_image')}}"
                                         onerror="{{asset('frontend/images/2.png')}}"
                                         alt="">
                                </a>
                                <div class="req-info">
                                    <h2>
                                        {{$item->product_name}}
                                    </h2>
                                    <div class="req-num">
                                        <div class="li-f">
                                            @if($item->quantity == 1)
                                                <h3 class="sm-f">{{__('web.piece')}} </h3>
                                            @else
                                                <h3 class="sm-f">{{__('web.pieces')}} </h3>
                                            @endif
                                            <h4>{{$item->quantity}} </h4>
                                        </div>

                                        <div class="price">
                                            <span>{{__('web.price')}}</span> {{$item->product_price*$item->quantity.' '.__('web.egp')}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                @foreach($previous_orders as $previous_order)
                        <h1> {{__('web.previous_orders')}}</h1>
                    @foreach($previous_order->orderItems as $item)

                        <div class="old">
                            <div class="old-req">

                            @if($previous_order->status == \App\Enums\OrderStatusEnum::completed())
                                    <h2 class="status"> {{__('web.status_'. \Illuminate\Support\Str::lower($previous_order->status)) }} </h2>
                                @elseif($previous_order->status == \App\Enums\OrderStatusEnum::cancelled())
                                    <h2 class="status cancel"> {{$previous_order->status}} </h2>
                                @endif
                                <a href="{{route('products.show',['id'=>$item->id])}}">
                                    <img src="{{\App\Models\Product::find($item->id)->getFirstMediaUrl('cover_image')}}"
                                         onerror="{{asset('frontend/images/2.png')}}"
                                         alt="">
                                </a>
                                <div class="req-info">
                                    <h1 style="color: #ae0028; font-weight: bold"> {{__('web.order_code').' '.$previous_order->order_code}}</h1>

                                    <h2>

                                        {{$item->product_name}}
                                    </h2>
                                    <div class="req-num">
                                        <div class="li-f">
                                            @if($item->quantity == 1)
                                                <h3 class="sm-f">{{__('web.piece')}} </h3>
                                            @else
                                                <h3 class="sm-f">{{__('web.pieces')}} </h3>
                                            @endif
                                            <h4>{{$item->quantity}} </h4>
                                        </div>
                                        <div class="price">
                                            <span>{{__('web.price')}}</span> {{$item->product_price*$item->quantity.' '.__('web.egp')}}
                                        </div>
                                    </div>
                                    @if($previous_order->status == \App\Enums\OrderStatusEnum::completed())
                                        <form method="post"
                                              action="{{route('order.review',['product_id'=>$item->id])}}"
                                              class="about-rating">
                                            @csrf
                                            <div class="product-review-stars">
                                                <input type="radio" id="star5" name="stars" value="5"
                                                       class="visuallyhidden"/><label for="star5"
                                                                                      title="Rocks!">★</label>
                                                <input type="radio" id="star4" name="stars" value="4"
                                                       class="visuallyhidden"/><label for="star4"
                                                                                      title="Pretty good">★</label>
                                                <input type="radio" id="star3" name="stars" value="3"
                                                       class="visuallyhidden"/><label for="star3"
                                                                                      title="Meh">★</label>
                                                <input type="radio" id="star2" name="stars" value="2"
                                                       class="visuallyhidden"/><label for="star2"
                                                                                      title="Kinda bad">★</label>
                                                <input type="radio" id="star1" name="stars" value="1"
                                                       class="visuallyhidden"/>


                                                <label for="star1" title="Sucks big time">★</label>
                                            </div>
                                            <textarea name="comment" id="" cols="60" rows="7"
                                                      placeholder=" {{__('web.write_comment')}}"></textarea>

                                            <input type="submit" class="add_comment" value="{{__('web.add')}}"/>
                                        </form>
                                </div>
                                <button class="btn-rating" onclick="addclass()">{{__('web.make_review')}}</button>
                                @endif
                            </div>

                        </div>
                    @endforeach
                @endforeach

            </div>


        </div>


    </div>
@endsection
@push('scripts')
    <script>
        const reting = document.querySelector(".about-rating");

        function addclass() {
            reting.classList.toggle("open")
        }
    </script>

@endpush
