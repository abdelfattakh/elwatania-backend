@extends('layout.app', ['current_page' => 'profile', 'title' => __('admin.addresses')])

@push('styles')
    @livewireStyles
@endpush

@section('body')
    <!--start main page-->
    <div class="main">
        <div class="container">
            @include('cards.sidebarInfo', ['page' => 'address'])

            @livewire('update-address',['address'=>$address])
        </div>
    </div>
    <!--end main page-->
@endsection

@push('scripts')
    @livewireScripts
@endpush
