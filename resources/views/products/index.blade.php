@extends('layout.app')

@section('body')
    @php
        /** @var \App\Models\Category|null $main_category */
        $main_category ??= null;
        $query = request('search') ?: $main_category?->getAttribute('name') ?: __('web.all');
    @endphp

    <div class="tv">
        <div class="container">
            <!-- Breadcrumb -->
            <nav
                style="
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
                    @if (filled($main_category))
                        @if (filled($main_category->getAttribute('parent_id')))
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ route('products.search', ['category_id' => $main_category->getRelation('parent')->getKey()]) }}">
                                    {{ $main_category->getRelation('parent')->name }}
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item">
                            {{ $main_category->getAttribute('name') }}
                        </li>
                    @endif
                </ol>
            </nav>
            <!-- end of Breadcrumb -->

            <!-- Show SubCats -->
            @if (filled($main_category) && filled($main_category->getRelation('children')))
                <div class="option">
                    @foreach ($main_category->getRelation('children') as $subCategory)
                        <a href="{{ route('products.search', ['category_id' => $subCategory->getKey()]) }}">
                            <div class="part">
                                <img src="{{ $subCategory->getFirstMediaUrl('category_image') }}"
                                    onerror="this.src='{{ asset('icon.svg') }}'" alt="{{ $subCategory->name }}" />
                                <h2>{{ $subCategory->name }}</h2>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            <!-- end of Show SubCats -->

            <div class="show">
                @if (filled($brands) || filled($min_price))
                    <!-- Filters -->
                    <div class="filter-products">
                        <h1>{{ __('web.filters') }}</h1>

                        <form method="GET" action="{{ route('products.search') }}">
                            <input type="hidden" name="category_id" value="{{ $main_category?->getKey() }}" />
                            <input type="hidden" name="search" value="{{ request('search') }}" />

                            <ul id="accordion" class="accordion">
                                @if (filled($min_price) && filled($max_price))
                                    <li>
                                        <div class="link">{{ __('web.price') }} <i class="fa fa-chevron-down"></i></div>
                                        <ul class="submenu">
                                            <li>
                                                <div class="range">
                                                    <div class="range-slider">
                                                        <span class="range-selected" id="selectedd"></span>
                                                    </div>
                                                    <div class="range-input">
                                                        <input type="range" class="min" min="{{ $min_price }}"
                                                            max="{{ $max_price }}" value="{{ request('min') }}"
                                                            step="0.01">
                                                        <input type="range" class="max" min="{{ $min_price }}"
                                                            max="{{ $max_price }}" value="{{ request('max') }}"
                                                            step="0.01">
                                                    </div>

                                                    <div class="range-price">
                                                        <label for="min">{{ __('web.min') }}</label>
                                                        <input type="number" name="min" min="{{ $min_price }}"
                                                            max="{{ $max_price }}" value="{{ request('min') }}"
                                                            step="0.01">
                                                        <label for="max">{{ __('web.max') }}</label>
                                                        <input type="number" name="max" min="{{ $min_price }}"
                                                            max="{{ $max_price }}" value="{{ request('max') }}"
                                                            step="0.01">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                @if (filled($brands))
                                    <li>
                                        <div class="link">{{ __('web.brands') }} <i class="fa fa-chevron-down"></i></div>
                                        <ul class="submenu">
                                            @foreach ($brands as $brand)
                                                <li>
                                                    <div class="form-check">
                                                        {{-- TODO: Need to select selected values. --}}
                                                        <input class="form-check-input" type="checkbox" name="brand_ids[]"
                                                            value="{{ $brand->getKey() }}" id="flexCheckDefault" />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            {{ $brand->getAttribute('name') }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif

                                <input class="apply" type="submit" value="{{ __('web.apply') }}" />
                            </ul>
                        </form>
                    </div>
                    <!-- end of Filters -->
                @endif

                <div class="products">
                    <div class="about-product">
                        <div class="unm-product">
                            <h2> {{ $query }} ({{ $products->total() }})</h2>
                        </div>

                        <div class="sort-product">
                            <select class="form-select form-select-lg " name="sort_by" aria-label=".form-select-lg example"
                                id="sort_by" onchange="updateQueryParameter(this)">
                                <option @ed(!filled(request('sort_by'))) disabled>
                                    {{ __('web.sort_by') }}
                                </option>
                                <option @selected(request('sort_by') == 'lowest_price') value="lowest_price">
                                    {{ __('web.lowest_price') }}
                                </option>
                                <option @selected(request('sort_by') == 'highest_price') value="highest_price">
                                    {{ __('web.highest_price') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="all-item" id="products">
                        @foreach ($products as $product)
                            @include('cards.product', ['product' => $product])
                        @endforeach
                    </div>

                    <div id="products-count">
                        @if ($products->hasMorePages())
                            <div class="show-line">
                                <span></span>
                                <h3>{{ __('web.showing', ['count' => $products->lastItem(), 'total' => $products->total()]) }}
                                </h3>
                            </div>
                            <div class="show-more">
                                <button class="load-more" data-page="{{ $products->currentPage() }}"
                                    onclick="viewMorePagination(this)"
                                    data-link="{{ request()->fullUrlWithoutQuery('page') }}" data-div="#products">
                                    {{ __('web.view_more') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('scripts.animation')

    <script>
        let rangeMin = {{ $min_price }};
        const range = document.querySelector(".range-selected");
        const rangeInput = document.querySelectorAll(".range-input input");
        const rangePrice = document.querySelectorAll(".range-price input");
        rangeInput.forEach((input) => {
            input.addEventListener("input", (e) => {
                let minRange = parseInt(rangeInput[0].value);

                let maxRange = parseInt(rangeInput[1].value);
                if (maxRange - minRange < rangeMin) {
                    if (e.target.className === "min") {
                        rangeInput[0].value = maxRange - rangeMin;

                    } else {
                        rangeInput[1].value = minRange + rangeMin;
                    }
                } else {
                    rangePrice[0].value = minRange;

                    rangePrice[1].value = maxRange;
                    range.style.left = (minRange / rangeInput[0].max) * 100 + "%";
                    range.style.right = 100 - (maxRange / rangeInput[1].max) * 100 + "%";
                }
            });
        });
    </script>

    <script>
        $(function() {
            const Accordion = function(el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;

                const links = this.el.find('.link');
                // Event
                links.on('click', {
                    el: this.el,
                    multiple: this.multiple
                }, this.dropdown)
            }

            Accordion.prototype.dropdown = function(e) {
                const $el = e.data.el;
                const $this = $(this),
                    $next = $this.next();

                $next.slideToggle();
                $this.parent().toggleClass('open');

                if (!e.data.multiple) {
                    $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
                }
            }

            new Accordion($('#accordion'), false);
        });
    </script>
@endpush
