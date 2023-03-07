@component('mail::message')

    <h1>{{ __('New contact form submission:') }}</h1>

    <p>{{ __('Name:') }} {{ $name }}<br>
        {{ __('Email:') }} {{ $email }}<br>
        {{ __('Message:') }}</p>

    <p>{{ $message }}</p>

    <p>{{ __('Thanks') }}, {{ config('app.name') }}</p>

@endcomponent
