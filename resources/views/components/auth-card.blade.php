<div class="bg-gray-200 dark:bg-gray-700 dark:text-white min-h-screen bg-gray-50 dark:bg-gray-700 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <section class="hero container max-w-screen-lg mx-auto text-center">
        <a href="{{ route('dashboard') }}">
            <h1>{{ config('app.name') }}</h1>
        </a>
    </section>

    <div class="w-full sm:max-w-md mt-6 mb-10 px-6 py-4 bg-white dark:bg-gray-900 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
