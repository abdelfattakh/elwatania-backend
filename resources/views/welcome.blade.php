<!DOCTYPE html>
@php
    $lang = app()->getLocale();
    $dir = config('app.available_locales')[$lang]['dir'] ?? 'ltr';
@endphp

<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | Navigation</title>

    <link rel="shortcut icon" href="{{ asset('icon.svg') }}" type="image/x-icon"/>
    <link type="text/css" rel="stylesheet" href="https://colorlib.com/etc/404/colorlib-error-404-8/css/style.css"/>
    <meta name="robots" content="noindex, follow">
</head>
<body>
<div id="notfound">
    <div class="notfound">
        <img src="{{ asset('icon.svg') }}" width="100">
        <br>
        <br>
        <p>Panel</p>
        <a href="{{ config('filament.path') }}">Master Admin Panel</a>
        <br>
        <br>
        <p>Docs</p>
        <a href="/docs">Docs</a>
        <br>
        <br>
        <p>Applications</p>
        <a href="#">Android</a>
        <a href="#">Ios</a>
        <br>
        <br>
        <p>Contacts</p>
        <a href="mailto:hello@aquadic.com">Mail: hello@aquadic.com</a>
        <br>
        <br>
        <p class="mt-8 text-center text-xs text-80">
            &copy; {{ date('Y') }} {{ config('app.name') }}
            <span class="px-1">&middot;</span>
            v-{{ config('app.version', '0.0.0') }}
            @env('local')
                <span class="px-1">&middot;</span>
                lv-{{ \Illuminate\Foundation\Application::VERSION }}
                <span class="px-1">&middot;</span>
                pv-{{ PHP_VERSION }}
            @endenv
        </p>
    </div>
</div>
</body>
</html>
