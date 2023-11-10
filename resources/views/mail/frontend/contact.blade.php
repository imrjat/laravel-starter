@component('mail::message')

## {{ __('New contact form submission:') }}

**{{ __('Name:') }}** {{ $name }}

**{{ __('Email:') }}** {{ $email }}

**{{ __('Message:') }}**

{{ $message }}

{{ __('Thanks') }}, {{ config('app.name') }}

@endcomponent
