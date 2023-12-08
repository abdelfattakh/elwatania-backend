@extends('layout.app', ['current_page' => 'product', 'title' => $product->getAttribute('name')])

@section('body')
    @php
        /** @var \App\Models\Product $product */
        $isFav = auth('web')->check() && \Maize\Markable\Models\Favorite::has($product, auth('web')->user());
    @endphp

        <!--start tv product-->

    <div class="tv-product">


        <div class="container">
            <nav style="
            --bs-breadcrumb-divider: url(
              &#34;data:image/svg + xml,
              %3Csvgxmlns='http://www.w3.org/2000/svg'width='8'height='8'%3E%3Cpathd='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z'fill='%236c757d'/%3E%3C/svg%3E&#34;
            );
            "
                 aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            {{ __('web.home') }}
                        </a>
                    </li>

                    @if(filled($product->getAttribute('category_id')))
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.search', ['category_id'=> $product->getAttribute('category_id')]) }}">
                                {{ $product->getRelation('category')->getAttribute('name') }}
                            </a>
                        </li>
                    @endif

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $product->getAttribute('name') }}
                    </li>
                </ol>
            </nav>

            <div class="tv-about">
                <!-- Product Images -->
                <div class="tv-image" data-aos="zoom-in">
                    <div style="position: relative">
                        <img
                            style="width: 22px" alt="favourite"
                            class="fav product-fav-{{ $product->getKey() }}"
                            onclick="addFavourite({{ $product->getKey() }})"
                            src="{{ asset($isFav ? 'frontend/images/heart_on.svg' : 'frontend/images/heart_red_dark_off.svg') }}?v {{ config('app.version') }}">
                    </div>

                    <div class="big-image">
                        @if($product->relationLoaded('coverImage') && $product->getRelation('coverImage'))
                            {{ $product->getRelation('coverImage')?->img('', ['style' => 'width: 579px;height: 501px;', 'alt' => $product->getAttribute('name'), 'onerror' => "this.src='" . asset('icon.svg') . "'", 'id'=>'img_show']) }}
                        @else
                            <img style="width: 579px;height: 501px;"
                                 id="img_show" src="{{ asset('icon.svg?v' . config('app.version')) }}" alt=""/>
                        @endif
                    </div>

                    @if($product->relationLoaded('image') && filled($product->getRelation('image')))
                        <div class="sm-image">
                            <ul>
                                @foreach($product->getRelation('image') as $image)
                                    <li>
                                        {{ $image?->img('', ['style' => 'width:129px ;height:103px','alt' => $product->getAttribute('name'), 'onerror' => "this.src='" . asset('icon.svg') . "'",'class'=>'def_img'] ) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <!-- end of Product Images -->

                <div class="tv-details" data-aos="zoom-in">
                    @if($product->is_available==0)
                        <p style="color: #AE0028; font-size: 26px">{{__('web.out_of_stock')}}</p>
                    @endif
                    <h1>
                        {{ $product->getAttribute('name') }}
                    </h1>

                    <div class="rev">
                        <div class="stars-tv">
                            <ul>
                                @for($i = 0; $i < $product->getRelation('reviews')->avg('stars') ; $i++)
                                    <li>
                                        <img
                                            src="{{ asset('frontend/images/star_on.svg?v' . config('app.version')) }}"
                                            alt="stars"/>
                                    </li>
                                @endfor
                                @for($i = 0; $i < (5 - $product->getRelation('reviews')->avg('stars')); $i++)
                                    <li>
                                        <img
                                            src="{{ asset('frontend/images/star_off.svg?v' . config('app.version')) }}"
                                            alt="stars"/>
                                    </li>
                                @endfor
                            </ul>
                        </div>

                        @if($product->getRelation('reviews')->count() == 1)
                            <h2>{{ $product->getRelation('reviews')->count() }} {{ __('web.review') }} </h2>
                        @else
                            <h2>{{ $product->getRelation('reviews')->count() }} {{ __('web.reviews') }} </h2>
                        @endif
                    </div>

                    <div class="all-salary">
                        @if(filled($product->discount_value) && $product->discount_value !=0)
                            <div class="price">
                                <h2>{{ $product->final_price }}</h2>
                                <h3>{{ __('web.egp') }}</h3>
                            </div>
                            <p style="font-size: 16px; color:#E23734;text-decoration: line-through;"
                               class="salary before-discount">{{ $product->price }} {{ __('web.egp') }}</p>
                        @else
                            <div class="price">
                                <h2>{{ $product->price }}</h2>
                                <h3>{{ __('web.egp') }}</h3>
                            </div>
                        @endif
                    </div>

                    <div class="tv-dli">
                        <h3>{{ $product->getAttribute('shipping_time') }}</h3>
                    </div>
                    @if($product->is_available ==1)
                        <div class="add-del">
                            <button class="tv-del" type="button" id="tv-del">-</button>
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <input type="number" class="num-tv" id="num-tv" name="quantity" readonly value=1>

                            <button class="tv-add" type="button" id="tv-add">+</button>
                        </div>
                    @endif
                    <span class="line-tv"></span>
                    <h2 class="tv-data">
                        {!! $product->getAttribute('general_description') !!}
                    </h2>
                    <span class="line-tv"></span>

                    <a href="#">
                        @if($product->is_available ==1)
                            <button class="add-to"
                                    onclick="AddToCart({{$product}})">{{ __('web.add_to_basket') }}</button>
                        @else
                            <button class="add-to_q" style="background-color:#D3D3D3; "
                                    onclick="failedAddingToCart()">{{ __('web.add_to_basket') }}</button>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--end tv product-->

    <!-- start collaps -->
    <div class="collaps-tv">
        <div class="container">
            <details class="">
                <summary>{{ __('web.product_specification') }}</summary>
                <p>
                    {!!  $product->getAttribute('general_description') !!}
                </p>
            </details>
            <details class="">
                <summary> {{ __('web.product_Technical_specification') }}</summary>
                <p>
                    {!! $product->getAttribute('technical_description') !!}
                </p>
            </details>
            <details class="">
                <summary>{{ __('web.Exchange_and_return_policy') }}</summary>
                <p>
                    {!!  $return_policy !!}
                </p>
            </details>
            @if($product->getRelation('reviews')->count()>0)
            <details class="rating">
                <summary>{{ __('web.reviews') }} {{ $product->getRelation('reviews')->count() }}</summary>
                <ul class="ul-rating">

                    @foreach($product->getRelation('reviews') as $review)

                        <li class="li-rating">
                            <div class="rating-about">
                                <h3>{{ $review->user_name }}</h3>
                                <p>{{ $review->comment }}</p>
                            </div>
                            <div class="stars-tv">
                                <ul>
                                    @for($i=0; $i < $review->stars; $i++)
                                        <li>
                                            <img src="{{ asset('frontend/images/path 53.svg') }}" alt="stars"/>
                                        </li>
                                    @endfor
                                    @for($i=0; $i < (5-$review->stars); $i++)
                                        <li>
                                            <img src="{{ asset('frontend/images/path 57.svg') }}" alt="stars"/>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </details>
                @endif

        </div>
    </div>

    <!--===================================================================================-->

    <!-- start owl two -->
    <!-- TESTIMONIALS -->

@if(filled($related_products))
    <section class="testimonials bg-t">
        <div class="container">
            <h2>{{ __('web.similar_products') }}</h2>
            <div class="row" data-aos="zoom-in">
                <div class="col-sm-12">
                    <div id="customers-testimonials2"   class="owl-carousel  owl_testimonials">
                        @foreach($related_products as $p)
                            @include('cards.product', ['product' => $p])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- END OF TESTIMONIALS -->
    <!-- end owl two -->

    <!--===================================================================================-->
    <!-- start  support -->
    <div class="support">
        <div class="container">
            <h2>{{ __('web.support_product_owners') }}</h2>
            <div class="about">
             @if(filled($product->getRelation('productGuideFile')))
                <div class="manual" data-aos="fade-down">

                    <img src="{{ asset('frontend/images/manual.svg') }}" alt=""/>
                    <div class="sup-info">
                        <h3>{{ __('web.download_product_file') }}</h3>
                        <a href="{{route('general.downloadProductGuide',['product_id'=>$product->id])}}">{{ __('web.download_now') }}</a>
                    </div>
                </div>
                @endif
                <div class="connect" data-aos="fade-down">
                    <img src="{{ asset('frontend/images/faq.svg') }}" alt=""/>
                    <div class="sup-info">
                        <h3>{{ __('web.contact_us_for_more') }}</h3>
                        <a href="{{route('contactUs')}}">{{ __('web.contact_us_now') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end  support -->
    <!--===================================================================================-->
@endsection

@push('scripts')
        @include('scripts.animation')
        @include('scripts.carousel')
    <script>
        const del = document.getElementById("tv-del");
        const add = document.getElementById("tv-add");
        let val = document.getElementById("num-tv").value;
        console.log(val)
        console.log(document.getElementById("num-tv"))
        let num = 1;
        console.log(typeof val);

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
        const img_show = document.getElementById("img_show")
        const def_img = document.getElementsByClassName("def_img")
        const ahmed = function () {
            img_show.src = this.src
        }
        for (i = 0; i <= def_img.length; i++) {
            def_img[i].addEventListener("click", ahmed)
        }
    </script>
@endpush
