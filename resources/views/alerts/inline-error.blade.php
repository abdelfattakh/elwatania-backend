@if(isset($field) && filled($field) && $errors->has($field))
    <div class="error text-danger">
        <p class="worng-msg" style="color: red">
            {{ $errors->first($field) }}
        </p>
    </div>
@endif
