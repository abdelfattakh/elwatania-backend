@extends('layout.app')
@section('body')

    <div class="main request">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'main'])
            <div class="req-about">
                @if(filled($last_order))
                    <h1> {{__('web.latest_order')}}</h1>
                    @foreach($last_order->orderItems as $item)
                        <div class="old">
                            <div class="old-req">
                                @if($last_order->status == \App\Enums\OrderStatusEnum::completed())
                                    <h2 class="status"> {{$last_order->status}} </h2>
                                @elseif($last_order->status == \App\Enums\OrderStatusEnum::cancelled())
                                    <h2 class="status"> {{__('web.status_'.\Illuminate\Support\Str::lower($current_order->status))}}</h2>
                                @endif
                                <a href="{{route('products.show',['id'=>$item->id])}}">
                                    <img src="{{\App\Models\Product::find($item->id)->getFirstMediaUrl('cover_image')}}"
                                         onerror="{{asset('frontend/images/2.png')}}"
                                         alt="">
                                </a>
                                <div class="req-info">
                                    <h1 style="color: #ae0028; font-weight: bold"> {{__('web.order_code').' '.$last_order->order_code}}</h1>

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
                    <div class="old">
                        <di class="old-req">
                            <div class="part"
                                 id="selectedAddress">
                                <h2> {{__('web.address_label')}} </h2>

                                <ul>
                                    <li style="display: flex; align-items: center;gap: 90px;margin-bottom: 16px;">
                                        <p style="    font-size: 20px;font-weight: 400; color: #707070;">{{__('web.name')}}</p>
                                        <h3>{{$address->name.' '.$address->family_name}}</h3>
                                    </li>
                                    <li style="display: flex; align-items: center;gap: 90px;margin-bottom: 16px;">
                                        <p style="    font-size: 20px;font-weight: 400; color: #707070;">{{__('web.address_label')}}</p>
                                        <h3>{{$address->street_name}}</h3>
                                    </li>
                                    <li style="display: flex; align-items: center;gap: 90px;margin-bottom: 16px;">
                                        <p style="    font-size: 20px;font-weight: 400; color: #707070;">{{__('web.phone_number')}}</p>
                                        <h3>{{$address->phone}}</h3>
                                    </li>
                                </ul>
                            </div>
                        </di>
                        @endif
                        @if(!filled($last_order))
                            <div class="main-about">
                                <h1>{{__('web.hello').' '.auth()->user()->full_name}}</h1>
                                <div class="part">
                                    <h2>{{__('web.latest_order')}}</h2>
                                    <p>{{__('web.go_shopping_recommendation')}}</p>
                                    <a href="{{route('home')}}">
                                        <button> {{__('web.go_shopping')}}</button>
                                    </a>
                                </div>
                                <div class="part">
                                    <h2>{{__('web.shipping_address')}}</h2>
                                    <p>{{__('web.add_address_recommendation')}}</p>
                                    <a href="{{route('forms')}}">
                                        <button> {{__('web.add_address')}}</button>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
            </div>
        </div>
@endsection

