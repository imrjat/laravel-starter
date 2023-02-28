@auth
    <div x-data="{ isOpen: false }">
        <div>
            <button @click="isOpen = !isOpen" class="text-gray-900 pt-3 focus:outline-none">
                {{ auth()->user()->name }}
            </button>
        </div>

        <div
            x-show.transition="isOpen"
            @click.away="isOpen = false"
            class="origin-top-right absolute right-0 mt-1 mr-3 w-48">
            <div class="relative z-30 rounded-b-md bg-white border border-gray-100 dark:bg-gray-700 shadow-xs">

                <hr>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-400">{{ __('Log out') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="post">
                    {{ csrf_field() }}
                </form>

            </div>

        </div>
    </div>
@endauth