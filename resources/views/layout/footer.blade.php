<div class="newfooter">
    <div class="container">
        <footer class="footer-dec">
            <div class="l-footer">
                <ul>
                    <li class="head-footer r-title">
                        <h3>{{ __('web.be_first_one_to_know') }}</h3>
                    </li>

                    <li>
                        <h4>{{ __('web.be_updated_offer') }}</h4>
                        <form action="{{ route('offers') }}" method="post">
                            @csrf
                            <div class="email">
                                <input type="email"
                                       name="email"
                                       placeholder="{{ __('web.send_mail') }}"
                                       value="{{ old('email', auth('web')->user()?->getAttribute('email')) }}"/>
                                @include('alerts.inline-error', ['field' => 'email'])

                                <button type="submit" onclick="toaster()">
                                    <img src="{{ asset('frontend/images/message.svg') }}" alt=""/>
                                </button>
                            </div>
                        </form>
                    </li>

                    <li class="imagestore">
                        <a href="#">
                            <!-- TODO: Store Link -->
                            <img src="{{ asset('frontend/images/playstore.svg') }}" alt="google-play"/>
                        </a>
                        <a href="#">
                            <!-- TODO: Store Link -->
                            <img src="{{ asset('frontend/images/appstore.svg') }}" alt="App-store"/>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="r-footer">
                <ul>
                    <li class="r-title"> {{ __('web.main_category') }}</li>
                    @foreach($data['parentcategories'] as $parentcategorie)
                        <li>
                            <a href="{{ route('products.search', ['category_id' => $parentcategorie->id]) }}">
                                <span> {{ $parentcategorie->name }} </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="r-footer">
                <ul>
                    <li class="r-title"> {{ __('web.about_watania') }}</li>
                    @foreach($data['about_pages'] as $page)
                        <li><a href="{{route('pages.show',['page'=>$page->id])}}">{{$page->title}}</a></li>
                    @endforeach

                </ul>
            </div>
            <div class="r-footer">

                <ul>

                    <li class="r-title">{{ __('web.customer_service') }}</li>
                    @foreach($data['customer_pages'] as $page)
                        <li><a href="{{route('pages.show',['page'=>$page->id])}}">{{$page->title}}</a></li>
                    @endforeach
                    <li><a href="{{route('contactUs')}}">{{ __('web.contact_us') }}</a></li>
                </ul>
            </div>
        </footer>
        <!--start copyright-->

        <div class="copy">
            <h4>{{ __('web.all_rights') }}</h4>
        </div>
    </div>
</div>

