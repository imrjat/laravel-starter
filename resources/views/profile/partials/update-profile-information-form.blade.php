<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <x-form method="patch" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        <x-form.input name="name" :label="__('Name')">{{ old('name', $user->name) }}</x-form.input>
        <x-form.input name="email" :label="__('Email')" type="email" autocomplete="username">{{ old('email', $user->email) }}</x-form.input>

        <div class="flex items-center gap-4">
            <x-form.submit>{{ __('Save') }}</x-form.submit>

            @if (session('status') === 'profile-updated')
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
