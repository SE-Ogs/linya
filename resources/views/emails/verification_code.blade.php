@component('mail::message')
# Code Verification

Use this verification code:

@component('mail::panel')
<strong style="font-size: 20px; letter-spacing: 2px;">{{ $code }}</strong>
@endcomponent

The code will expire in 10 minutes.

If you didn’t request this, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
