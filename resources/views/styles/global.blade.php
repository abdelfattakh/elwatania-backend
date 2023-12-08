<link rel="shortcut icon" href="{{ asset('icon.svg?v' . config('app.version')) }}" type="image/x-icon"/>

@if($dir='rtl')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N"
        crossorigin="anonymous"
    />
@else
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}"/>
@endif

<link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}"/>
<link rel="stylesheet" href="{{ asset('frontend/css/snackbar.min.css') }}"/>
