@php
    /** @var \App\Models\Category $category */
@endphp

<a data-aos="fade-up"
   href="{{ route('products.search', ['category_id'=> $category->getKey()]) }}">
    <div class="part">
        @if($category->relationLoaded('image')&& $category->getRelation('image'))
            {{ $category->getRelation('image')?->img('', ['alt' => $category->getAttribute('name'), 'onerror' => "this.src='" . asset('icon.svg') . "'"]) }}
        @else
            <img class="img-responsive" src="{{ asset('icon.svg?v' . config('app.version')) }}"  alt=""/>
        @endif

        <h1> {{ $category->getAttribute('name') }}</h1>
    </div>
</a>
