<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    @include('errors.messages')

    <x-form method="put" action="{{ route('password.update') }}" class="mt-6 space-y-6">

        <x-form.input name="current_password" :label="__('Current Password')" type="password" autocomplete="current-password"></x-form.input>
        <x-form.input name="password" :label="__('New Password')" type="password"></x-form.input>
        <x-form.input name="password_confirmation" :label="__('Confirm Password')" type="password"></x-form.input>

        <div class="flex items-center gap-4">
            <x-form.submit>{{ __('Save') }}</x-form.submit>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </x-form>
</section>
