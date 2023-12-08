<header class="header">
    <div class="container">
        <a class="nav-link active" aria-current="page"
           href="{{ route('change_locale', ['locale' => $lang == 'en' ? 'ar' : 'en']) }}">
            <img id="change-lang" src="{{ asset('frontend/images/lang.svg') }}" alt=""/>
        </a>
        <div class="conact">
{{--            <img src="{{ asset('frontend/images/arrow_down.svg') }}" alt=""/>--}}
{{--            <h2>{{ __('web.support') }}</h2>--}}
        </div>
        <a href="{{ route('contactUs') }}">{{ __('web.contact_us') }}</a>
    </div>
</header>

<section>
    <div class="container">
        <nav class="navigation">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('frontend/images/logo.svg') }}" alt="Logo" class="logo-img"/>
            </a>
            <div class="main_search">
                <form action="{{ route('products.search') }}" method="GET">
                    <input type="search" name="search" placeholder="{{ __('web.search_for_product') }}" id=""/>
                    <button class="icon-search" type="submit">
                        <img src="{{ asset('frontend/images/search.svg') }}" alt="" srcset=""/>
                    </button>
                </form>
            </div>
            <div class="nav_action">
                @auth('web')
                    <div class="login">
                        <a href="{{ route('profile') }}" class="btn">
                            <img src="{{ asset('frontend/images/user_red.svg') }}" style="width: 22px;"
                                 alt=""/>
                            <h2>{{ auth()->user()->first_name.' '.auth()->user()->last_name }}</h2>
                        </a>
                        <ul class="logindrop">
                            <li><a href="{{ route('updateProfile') }}">{{ __('web.my_account') }}</a></li>
                            <li><a href="{{ route('request') }}">{{ __('web.my_orders') }}</a></li>
                            <li><a href="{{ route('perfect') }}">{{ __('web.favourites') }}</a></li>
                            <li class="mt">
                                <a type="submit" href="{{ route('logout') }}"
                                   class="text-danger">{{ __('web.log_out') }}</a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('basket') }}">
                        <img src="{{ asset('frontend/images/cart_on.svg') }}" style="width: 22px;"
                             alt="shopping_cart"/>
                    </a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn">
                        <img src="{{ asset('frontend/images/user_red.svg') }}" style="width: 22px;"
                             alt=""/>
                        <h2>{{ __('web.login') }}</h2>
                    </a>
                @endguest

                <div class="nav_toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
            </div>
        </nav>

        <ul class="nav_menu">

            @foreach($data['parentcategories'] as $parentcategorie)
                <li class="nav_list nav_list_menu">
                    <div class="nav_link">
                        <a href="{{ route('products.search', ['category_id' => $parentcategorie->id]) }}">
                            <span> {{ $parentcategorie->name }} </span>
                        </a>
                        @if(filled($parentcategorie->children))
                            <ion-icon name="chevron-down-outline">
                                <i class="fa-solid fa-angle-down"></i>
                            </ion-icon>
                        @endif
                    </div>

                    @if(filled($parentcategorie->children))
                        <div class="dropdown">
                            <div class="dropdown-inner">
                                @foreach($parentcategorie->children as $child)
                                    <div class="dropdown-item">
                                        <a href="{{ route('products.search', ['category_id' => $child->id]) }}">
                                            <h3 class="item-heading">{{ $child->name }}</h3>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
