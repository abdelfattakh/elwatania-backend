@extends('layout.app')
@section('body')
    <div class="main perfect">
        <div class="container" style="display: block; ">
            <div>
                <h1 @if(app()->getLocale() =='ar')style=" text-align: right;"
                    @endif  @if(app()->getLocale() =='en')style=" text-align: left" @endif>  {{$page->title}}</h1>
            </div>
            <div>

                <p @if(app()->getLocale() =='ar')style=" text-align: right"
                   @endif  @if(app()->getLocale() =='en')style=" text-align: left" @endif>
                    {!! $page->description !!}
                </p>
            </div>
        </div>
    </div>
    </div>

@endsection
@push('scripts')
    @include('scripts.animation')
    @include('scripts.carousel')
@endpush
