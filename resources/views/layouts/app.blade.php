<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? null }} - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

    <div x-data="{ sidebarOpen: false }" x-cloak>
        <div class="flex min-h-screen">

            @auth
                <!-- regular sidebar -->
                <div class="sidebar hidden flex-none w-full md:block md:w-60 px-4 bg-white dark:bg-gray-700 border-r dark:border-gray-500">

                    @if(auth()->user()->tenant->isOnTrial())
                        @php
                        $text = __("Trial ends in ").auth()->user()->tenant->trial_ends_at->diffInDays().__(" days");
                        @endphp

                        <div class='inline-flex items-center px-4 py-2 m-4 text-sm font-medium rounded-md text-white bg-blue-600 shadow-sm hover:bg-blue-500'>
                            @if (auth()->user()->isOwner())
                                <x-a href="{{ route('admin.billing') }}">{{ $text }}</x-a>
                            @else
                                {{ $text }}
                            @endif
                        </div>
                    @endif

                    @if(auth()->user()->tenant->isOnGracePeriod())
                        @php
                        $text = __("Subscription will end in ").auth()->user()->tenant->ends_at->diffInDays().__(" days");
                        @endphp
                        <div class='inline-flex items-center px-4 py-2 m-4 text-sm font-medium rounded-md text-white bg-blue-600 shadow-sm hover:bg-blue-500'>
                            @if (auth()->user()->isOwner())
                                <x-a href="{{ route('admin.billing') }}">{{ $text }}</x-a>
                            @else
                                {{ $text }}
                            @endif
                        </div>
                    @endif

                    @include('layouts.navigation')
                </div>

                <!--sidebar on mobile-->
                <div x-show="sidebarOpen" class="sidebar min-w-full px-4 bg-white dark:bg-gray-700 md:hidden">
                    @include('layouts.navigation')
                </div>
            @endauth

            <div id="main" class="w-full bg-gray-100 dark:bg-gray-600">

                @auth
                    <div class="bg-white dark:bg-gray-700 border-b dark:border-gray-500 flex justify-between px-2 py-2">

                        <div class="flex">
                            <button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none pl-1 pr-2">
                                <svg class="w-6 transition ease-in-out duration-150 text-gray-900 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex">
                            <livewire:admin.notifications-menu />
                            <livewire:admin.help-menu />
                            <livewire:admin.users.user-menu />
                        </div>
                    </div>
                @endauth

                <div class="px-7 py-5">
                    {{ $slot ?? '' }}
                </div>

            </div>

        </div>

    </div>

    <div class="flex justify-between text-sm px-4 py-2 bg-white dark:bg-gray-700 border-t dark:border-gray-500">
        <p>{{ __('Copyright') }} &copy; {{ date('Y') }} {{ config('app.name') }}</p>
        <p>{{ __('Built by') }} <a href="https://dcblog.dev">David Carr</a></p>
    </div>

    </body>
</html>
