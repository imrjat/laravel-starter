<x-guest-layout>

    @section('title', __('Login'))

    <x-form action="{{ route('login') }}">

        @include('errors.messages')

        <x-form.input name="email" :label="__('Email')">{{ old('email') }}</x-form.input>
        <x-form.input name="password" :label="__('Password')" type="password" />

        <div class="flex justify-between">
            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}">{{ __('Register') }}</a>
            @endif
        </div>

        <p><button type="submit" class="justify-center w-full btn btn-primary">Login</button></p>

    </x-form>

</x-guest-layout>
