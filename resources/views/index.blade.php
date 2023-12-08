@extends('layout.app', ['current_page' => 'home'])

@push('styles')
    @include('styles.animation')
    @include('styles.carousel')
@endpush

@section('body')
    @include('alerts.success')

    <!-- Banners -->
    <div class="imagecount">
        <div class="container">
            <div class="imagecount">
                <div class="container">
                    @if($banner?->relationLoaded('image'))
                        {{ $banner->getRelation('image')?->img('', ['style' => 'width: 100% ;height: 100%;max-height: 770px;object-fit: cover;', 'onerror' => "this.src='" . asset('logo-dark.svg') . "'"]) }}
                    @else
                        <img src="{{ asset('logo-dark.svg') }}" style="width: 100% ;height: 100%;   max-height: 770px; object-fit: cover;" alt=""/>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- end of Banners -->

    <!-- Categories -->
    <div class="section">
        <div class="container">
            <h2>{{ __('web.categories') }}</h2>
            <div class="allimage">
                @foreach ($parentcategories as $category)
                    @include('cards.category', ['category' => $category])
                @endforeach
            </div>
        </div>
    </div>
    <!-- end of Categories -->

    <!-- Exclusive Products -->
    <section class="testimonials">
        <div class="container">
            <h2>{{ __('web.exclusive_products') }}</h2>
            <div class="row " data-aos="zoom-in">
                <div class="col-sm-12">
                    <div id="exclusive_products" class="owl-carousel owl_testimonials">
                        @foreach ($exclusiveProducts as $product)
                            @include('cards.product', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
            <a href="{{ route('products.search',['exclusive' => true]) }}">
                <button class="tsok">{{ __('web.exclusive_shopping') }}</button>
            </a>
        </div>
    </section>
    <!-- end of Exclusive Products -->

    <!-- Category Products -->
    @foreach ($categories as $category)
        <section class="testimonials">
            <div class="container">
                <h2>{{ $category->getAttribute('name') }}</h2>
                <div class="row " data-aos="zoom-in">
                    <div class="col-sm-12">
                        <div id="category_products_{{ $category->getKey() }}"
                             class="owl-carousel  owl_testimonials">
                            @foreach ($category->products as $product)
                                @include('cards.product', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('products.search',['category_id' => $category->getKey()]) }}">
                    <button class="tsok">
                        {{ __('web.go_shopping_category', ['category' => $category->getAttribute('name')]) }}
                    </button>
                </a>
            </div>
        </section>
    @endforeach
    <!-- end of Category Products -->

    <!--Image App -->
    <div class="app" id="app">
        <img id="rot" src="{{ asset('frontend/images/app_banner_empty.png') }}" class="bigimage"
             alt=""/>
        <div class="context">
            <div class="container">
                <div class="about-app">
                    <h2>{{ __('web.elwatania_application') }}</h2>
                    <h3>{{ __('web.help_you') }}</h3>
                    <h3>{{ __('web.available_on') }}</h3>
                </div>
                <div class="logostor">
                    <a href=""><img src="{{ asset('frontend/images/playstore.svg') }}" alt=""/></a>
                    <a href=""><img src="{{ asset('frontend/images/appstore.svg') }}" alt=""/></a>
                </div>
            </div>
        </div>

    </div>
    <!--end of Image App -->

    <!-- Brands -->
    <div class="alamat">
        <div class="container">
            <h2>{{ __('web.our_brands') }}</h2>
            <div class="logo-company">
                @foreach($brands as $brand)
                    @include('cards.brand', ['brand' => $brand])
                @endforeach
            </div>
        </div>
    </div>
    <!-- end of Brands-->
@endsection

@push('scripts')
    @include('scripts.animation')
    @include('scripts.carousel')
@endpush
