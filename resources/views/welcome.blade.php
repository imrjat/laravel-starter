<x-front-layout>
    @section('title', 'Domain Mapper');
    <main class="mx-auto max-w-screen-xl px-4 sm:mt-12 sm:px-6 md:mt-15 mb-10">
        <div class="text-center">

            <h2 class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl">
                <span class="dark:text-gray-100">Automatic</span> <span class="text-blue-500">Domain Tracking</span>
            </h2>

            <p class="mt-3 max-w-md mx-auto text-base text-gray-700 dark:text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Domains are often purchased from multiple providers, keeping track of where a domain is and its DNS
                settings can be tricky.
            </p>

            <p class="mt-3 max-w-md mx-auto text-base text-gray-700 dark:text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Domain Mapper solves this by listing all your domains in one place. View your DNS settings and receive
                reminders to renew your domains.
            </p>

            @if(config('admintw.is_live'))
                {

                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">Dashboard</a>
                        @else
                            <a href="{{ route('register') }}"
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">Start
                                free {{ env('TRIAL_DAYS') }} days trial</a>
                        @endauth
                    </div>
                </div>

            @else

                <div class="mx-auto px-4 py-12">
                    <div class="px-6 py-6 bg-gray-800 dark:bg-gray-700 rounded-lg md:py-12 md:px-12 lg:py-16 lg:px-16 xl:flex xl:items-center">
                        <div class="xl:w-0 xl:flex-1">

                            <h2 class="text-2xl leading-8 font-extrabold tracking-tight text-white sm:text-3xl sm:leading-9">
                                Domain Mapper will be released soon
                            </h2>

                            <p class="mt-3 max-w-3xl text-lg leading-6 text-blue-200" id="newsletter-headline">
                                Sign up for our newsletter to be notified<br> when {{ config('app.name') }} goes live.
                            </p>

                        </div>
                        <div class="mt-8 sm:w-full sm:max-w-md xl:mt-0 xl:ml-8">

                            <div id="mc_embed_signup"></div>
                            <form class="sm:flex validate" aria-labelledby="newsletter-headline"
                                  action="https://app.us20.list-manage.com/subscribe/post?u=19c2e13967fee3206516c55c2&amp;id=6402dcf425"
                                  method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                  target="_blank"
                                  novalidate>
                                <input aria-label="Email address" type="email" name="EMAIL" id="mce-EMAIL" required
                                       class="appearance-none w-full px-5 py-3 border border-transparent text-base leading-6 rounded-md text-gray-900 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 transition duration-150 ease-in-out"
                                       placeholder="Enter your email"/>
                                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                                    <button
                                            class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:bg-blue-400 transition duration-150 ease-in-out">
                                        Notify me
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            @endif

        </div>
    </main>

    <div class="bg-gray-100 dark:bg-gray-700 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <img class="rounded-lg" src="{{ url('images/domainmapper-domains.png') }}" alt="{{ config('app.name') }}">
        </div>
    </div>

    @include('front.features')
    @include('front.pricing')
    @include('front.faq')
    @include('front.contact')


</x-front-layout>