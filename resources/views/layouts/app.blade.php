<!DOCTYPE html>
<html
        lang="en"
        class="scroll-smooth dark"
        x-data="{ darkMode: localStorage.getItem('dark') === 'true'} "
        x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
        x-bind:class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        @stack('styles')
        @stack('scripts')
        <livewire:styles/>
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased">

    <div x-data="{ sidebarOpen: false }" x-cloak>
        <div class="flex min-h-screen">

            @auth
                <!-- regular sidebar -->
                <div class="sidebar hidden flex-none w-full md:block md:w-60 px-4 bg-primary dark:bg-gray-700">
                    @include('layouts.navigation')
                </div>

                <!--sidebar on mobile-->
                <div x-show="sidebarOpen" class="sidebar min-w-full px-4 bg-primary dark:bg-gray-700 md:hidden">
                    @include('layouts.navigation')
                </div>
            @endauth

            <div id="main" class="w-full bg-gray-100 dark:bg-gray-600">

                @auth
                    <div class="flex justify-between mb-5 bg-white dark:bg-gray-700 border-b-4 border-primary px-2 py-2">

                        <div class="flex">
                            <button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none pl-1 pr-2">
                                <svg class="w-6 transition ease-in-out duration-150 text-gray-900 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                                </svg>
                            </button>

                            <livewire:admin.search />

                        </div>

                        <div class="flex">
                            <button type="button" class="text-gray-700 dark:text-gray-100 mr-1 mt-1" @click="darkMode = !darkMode">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" x-show="darkMode">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>

                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" x-show="!darkMode">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                                </svg>
                            </button>
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

        <div class="bg-white dark:bg-gray-900 dark:text-gray-300 p-5 flex justify-between text-xs">
            <div>{{ __('Copyright') }} &copy; {{ date('Y') }} {{ config('app.name') }}</div>
        </div>

    </div>

    <livewire:scripts />
    </body>
</html>
