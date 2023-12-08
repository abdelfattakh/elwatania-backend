@extends('layout.app', ['current_page' => 'profile', 'title' => __('default.profile.profile')])

@push('styles')
    @livewireStyles
@endpush

@section('body')
    <!--start main page-->
    <div class="main ">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'address'])

            <livewire:forms></livewire:forms>
        </div>
    </div>
    <!--end main page-->
@endsection

@push('scripts')
    @livewireScripts
@endpush
