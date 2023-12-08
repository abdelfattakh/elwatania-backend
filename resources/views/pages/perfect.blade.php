@extends('layout.app')
@section('body')

    <!--start main page-->
    <div class="main perfect">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'favourites'])
            <div class="main-about">
                <h1 class="h1-per">{{__('web.favourites')}}</h1>
                <div class="all-love">
                    @foreach($products as $product)
                        <div class="item">
                            <div style="position: relative">
                                @auth
                                    <img id="addfavourites" onclick="addFavourite({{$product->id }},this)"
                                         @if(\Maize\Markable\Models\Favorite::has($product, auth()->user()))src="{{asset('frontend/images/heart_on.svg')}}"
                                         @else src="{{ asset('frontend/images/heart_red_dark_off.svg') }}"
                                         @endif
                                         style="width:  22px" class="fav" alt=""/>
                                @endauth

                                @guest
                                    <img id="addfavourites"
                                         onclick="window.location.href='{{route('login')}}'"
                                         src="{{ asset('frontend/images/heart_red_dark_off.svg') }}"
                                         style="width:  22px" class="fav" alt=""/>
                                @endguest
                            </div>
                            <div class="shadow-effect">
                                @if($product->discount_value)

                                    <div class="sal">
                                        <img
                                            src="{{asset('frontend/images/sale.svg')}}"
                                            style=" width:74px;"
                                            alt=""
                                        />
                                        <p>{{$product->discount_value}}%</p>
                                    </div>
                                @endif
                                <img class="img-responsive"
                                     src="{{$product->getFirstMediaUrl('cover_image')}}"
                                     onerror="this.src='{{ asset('icon.svg') }}'"
                                     alt=""/>

                                <div class="item-details">

                                    <h5>
                                        {{$product->name}}
                                    </h5>
                                    <div class="add">
                                        <img
                                            src="{{asset('frontend/images/cart_red.svg')}}"
                                            alt=""
                                        />

                                        <p style="color: #AE0028;   font-size:13px;">اضف الي السلة</p>

                                        <div class="all-salary">

                                            @if($product?->discount_value)
                                                <p style="font-size: 14px; text-decoration: line-through;"
                                                   class="salary before-discount  ">{{ $product->price }}
                                                    جم</p>
                                                <p class="salary">{{ $product->final_price }}
                                                    جم</p>
                                            @else
                                                <p class="salary">{{ $product->price }}
                                                    جم</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div style="margin: auto" class="show-line">
                    <span></span>
                    <h3>عرض 6 من 21</h3>
                </div>
                <div style="margin: auto" class="show-more">
                    <button>عرض المزيد</button>
                </div>
            </div>
        </div>

@endsection
