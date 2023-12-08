@if ((isset($message) && filled($message)) || filled(session()->get('message')))
    <div class="" role="alert">
        <p style="color: #FF0000; font-size:16px;">{{ $message ?? session()->get('message') }}</p>
        <div style="display: flex; gap:4px;">
            @if (filled(session()->get('user')))
            <p style="color: #949494; font-size:14px;"> {{ __('web.resend_email') }}</p>
            <a style="color: #AE0028; font-size:14px; font-weight:bold; text-decoration:underline;" href="{{ route('general.sendVerificationEmail') }}">
                {{__('web.resend')}}
            </a>
        @endif
        </div>
    </div>
@endif
