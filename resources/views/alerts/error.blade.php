@if($errors->any())
    <div class="alert alert-danger container text-center" role="alert">
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    </div>
@endif
