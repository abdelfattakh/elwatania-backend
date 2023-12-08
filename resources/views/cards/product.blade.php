@php
    /** @var \App\Models\Product $product */
    $isFav = auth('web')->check() && \Maize\Markable\Models\Favorite::has($product, auth('web')->user());
    // TODO: if in cart show another icon.
@endphp

<div class="item" data-aos="zoom-in-down">
    <div style="position: relative">
        <img
            style="width: 22px" alt="favourite"
            class="fav product-fav-{{ $product->getKey() }}"
            onclick="addFavourite({{ $product->getKey() }})"
            src="{{ asset($isFav ? 'frontend/images/heart_on.svg' : 'frontend/images/heart_red_dark_off.svg') }}?v {{ config('app.version') }}">
    </div>
    <div class="shadow-effect">
        @if(filled($product->getAttribute('discount_value')) && $product->getAttribute('discount_value') !=0)
            <div class="sal">
                <img
                    src="{{ asset('frontend/images/sale.svg?v' . config('app.version')) }}"
                    style="width:74px;"
                    alt="sale"/>
                <p>{{ $product->getAttribute('discount_value') }}%</p>
            </div>

        @endif
        <a  style="margin: auto" href="{{ route('products.show',['id' => $product->getKey(), 'name' => str($product->getAttribute('name'))->slug()]) }}">

            @if($product->relationLoaded('coverImage')&& $product->getRelation('coverImage'))
                {{ $product->getRelation('coverImage')?->img('', ['id' => \Illuminate\Support\Str::orderedUuid(), 'class' => 'img-responsive', 'alt' => $product->getAttribute('name'), 'onerror' => "this.src='" . asset('icon.svg') . "'"]) }}
            @else
                <img class="img-responsive" src="{{ asset('icon.svg?v' . config('app.version')) }}" alt=""/>
            @endif
        </a>
        <div class="item-details">
            <h5>
                {{ $product->getAttribute('name') }}
            </h5>
            <div class="add">
                <button type="button">
{{--                    @dd($product->quantity)--}}
                    @if($product->is_available==0)
                        <p style="font-size: 14px; color: red">{{__('web.out_of_stock')}}</p>
                    @else
                        <img
                            src="{{ asset('frontend/images/cart_red.svg?v' . config('app.version')) }}"
                            class="product-cart-{{ $product->getKey() }}"
                            onclick="AddProductToCart({{ $product->getKey() }})"
                            alt="cart"/>
                    @endif
                </button>


                <div class="all-salary">
                    @if(filled($product->getAttribute('discount_value')) && $product->getAttribute('discount_value') !=0)
                        <p style="font-size: 14px; text-decoration: line-through;" class="salary before-discount">
                            {{ $product->price.' '.__('web.egp') }}
                        </p>
                        <p class="salary">{{ $product->final_price.' '.__('web.egp') }}</p>
                    @else
                        <p class="salary">{{ $product->price.' '.__('web.egp') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
