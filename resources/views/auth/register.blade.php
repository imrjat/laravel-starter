<x-guest-layout>

    @section('title', 'Register')

        <x-form action="{{ route('register') }}">
            <x-form.input type="text" :label="__('Name')" name="name">{{ old('name') }}</x-form.input>
            <x-form.input type="email" :label="__('Email')" name="email">{{ old('email') }}</x-form.input>

            <div class="alert alert-primary">
                <p class="text-white">Password must be at least 8 characters in length<br>
                    at least one lowercase letter<br>
                    at least one uppercase letter<br>
                    at least one digit</p>
            </div>

            <x-form.input type="password" :label="__('Password')" name='password'></x-form.input>
            <x-form.input type="password" :label="__('Confirm Password')" name='confirmPassword'></x-form.input>
            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('login') }}" class="pt-2 mr-5 underline">
                    {{ __('Already registered?') }}
                </a>

                <x-form.submit>{{ __('Register') }}</x-form.submit>
            </div>
        </x-form>


</x-guest-layout>
