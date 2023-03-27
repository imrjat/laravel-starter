<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <livewire:styles/>
</head>
<body class="dark:bg-gray-800 text-white">

<div class="relative" x-data="{ open: false }">

    <div class="relative pt-6">

        <div class="max-w-screen-xl mx-auto px-4 py-4 sm:px-6 border-b-2 border-gray-50 dark:border-gray-800">

            <nav class="relative flex items-center justify-between sm:h-10 md:justify-center">
                <div class="flex items-center flex-1 md:absolute md:inset-y-0 md:left-0">

                    <div class="flex items-center justify-between w-full md:w-auto">
                        <a href="{{ url('/') }}" aria-label="Home">
                            <h1 class="text-2xl tracking-tight leading-10 font-extrabold text-gray-900 sm:leading-none">
                                <span class="dark:text-gray-100">Domain</span> <span
                                        class="text-blue-600 dark:text-blue-500">Mapper</span>
                            </h1>
                        </a>
                    </div>

                    <div class="-mr-2 flex items-center md:hidden">
                        <button type="button" @click="open = true"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                id="main-menu" aria-label="Main menu" aria-haspopup="true">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="hidden md:block">
                    <a href="{{ url('/#features') }}"
                       class="ml-10 font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-400">Features</a>
                    <a href="{{ url('/#pricing') }}"
                       class="ml-10 font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-400">Pricing</a>
                    <a href="{{ url('/#faqs') }}"
                       class="ml-10 font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-400">FAQs</a>
                    <a href="{{ url('/#contact') }}"
                       class="ml-10 font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-400">Contact</a>
                </div>

                @if (config('admintw.is_live'))
                    <div class="hidden md:absolute md:flex md:items-center md:justify-end md:inset-y-0 md:right-0">

                        @auth

                            <ul class="nav navbar-nav navbar-right">
                                <span class="inline-flex rounded-md shadow">
                                    <a href="{{ url('dashboard') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-blue-700 transition duration-150 ease-in-out">
                                        {{ __('Dashboard') }}
                                    </a>
                                </span>

                                <span class="ml-2 inline-flex rounded-md shadow">
                                    <a href="{{ url('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-blue-700 transition duration-150 ease-in-out">
                                        {{ __('logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ url('logout') }}" method="post">
                                        {{ csrf_field() }}
                                    </form>
                                </span>
                            </ul>

                        @else

                            <ul class="nav navbar-nav navbar-right">
                            <span class="inline-flex rounded-md shadow">
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-blue-700 transition duration-150 ease-in-out">
                                    {{ __('Login') }}
                                </a>
                            </span>

                                <span class="ml-2 inline-flex rounded-md shadow">
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-600 text-white hover:bg-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-blue-700 transition duration-150 ease-in-out">
                                    {{ __('Start free trial') }}
                                </a>
                            </span>
                            </ul>

                        @endauth

                    </div>
                @endif
            </nav>
        </div>

        <div class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden"
             x-transition:enter="transition ease-out duration-100 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-show="open">
            <div class="rounded-lg shadow-md">
                <div class="rounded-lg bg-white dark:bg-gray-700 shadow-xs overflow-hidden" role="menu"
                     aria-orientation="vertical" aria-labelledby="main-menu">

                    <div class="px-5 pt-4 flex items-center justify-between">

                        <div>
                            <a href="{{ url('/') }}">
                                <h1 class="text-2xl tracking-tight leading-10 font-extrabold text-gray-900 sm:leading-none">
                                    <span class="dark:text-gray-100">Domain</span> <span
                                            class="text-blue-600 dark:text-blue-500">Mapper</span>
                                </h1>
                            </a>
                        </div>

                        <div class="-mr-2">
                            <button type="button" @click="open = false"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                    aria-label="Close menu">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                    </div>

                    <div class="px-2 pt-2 pb-3">
                        <a href="{{ url('/#features') }}"
                           class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                           role="menuitem">Features</a>
                        <a href="{{ url('/#pricing') }}"
                           class="mt-1 mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                           role="menuitem">Pricing</a>
                        <a href="{{ url('/#faqs') }}"
                           class="mt-1 mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                           role="menuitem">FAQs</a>
                        <a href="{{ url('/#contact') }}"
                           class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                           role="menuitem">Contact</a>
                    </div>

                    @if (config('admintw.is_live'))
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                               role="menuitem">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                               role="menuitem">Login</a>
                            <a href="{{ route('register') }}"
                               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-500 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                               role="menuitem">Start free trial</a>
                        @endauth
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

{{ $slot }}

<livewire:scripts/>
</body>
</html>