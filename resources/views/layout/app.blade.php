<!DOCTYPE html>
@php
    $lang = app()->getLocale();
    $dir = config('app.available_locales')[$lang]['dir'] ?? 'ltr';
    $title ??= __('web.home');
@endphp

<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="keywords" content="elwatania,electronics,mobile,Home appliances,refrigerators,Boilers"/>
    <title>{{ config('app.name') }} | {{ $title }}</title>
    {!! seo($product ?? null) !!}
    @include('styles.global')
    @stack('styles')
</head>

<body>
<!-- Navbar -->
@include('layout.navbar')
<!--end Navbar -->

<!-- Body -->
@yield('body')
<!--end Body -->

<!--start Footer -->
@include('layout.footer')
<!--end Footer -->
</body>

<!--start Script -->
@include('scripts.global')
@stack('scripts')
@include('scripts.common')
@includeWhen(auth('web')->check(), 'scripts.authenticated')
@includeWhen(!auth('web')->check(), 'scripts.unauthenticated')
<!--end Scripts -->
</html>
