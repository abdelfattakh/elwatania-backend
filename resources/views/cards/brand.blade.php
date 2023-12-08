@php
    /** @var \App\Models\Brand $brand */
@endphp

<a data-aos="fade-up"
   href="{{ route('products.search', ['brand_ids'=> [$brand->getKey()]]) }}">
    <div class="part">
        @if($brand->relationLoaded('image'))
            {{ $brand->getRelation('image')?->img('', ['alt' => $brand->getAttribute('name'), 'onerror' => "this.src='" . asset('icon.svg') . "'"]) }}
        @else
            <img class="img-responsive" src="{{ asset('icon.svg?v' . config('app.version')) }}" alt=""/>
        @endif
    </div>
</a>
