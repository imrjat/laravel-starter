<div>
    <h1>{{ __('Subscription') }}</h1>

    <div class="card">

{{--        @if (request('action') == 'success')--}}
            <div class="alert alert-info">
                {{ __('Success, you are now subscribed.') }}
            </div>
{{--        @endif--}}

{{--        @if ($status == 'Trial Expired')--}}
            <div class="alert alert-info">
                {{ __('Your trial has ended, please subscribe below to continue.') }}
            </div>
{{--        @endif--}}

        <div class="well dark:text-gray-300">
            <b>{{ __('Subscription Plan') }}:</b> <span class="label alert-info">$plan</span><br>
            <b>{{ __('Status') }}:</b> {{ $status }}<br>

{{--            @if ($cancelled === false && $status === 'active' || $status == 'trial')--}}
                <x-modal>
                    <x-slot name="trigger">
                        <button @click="on = true"
                                class="mt-2 px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-indigo-700 transition ease-in-out duration-150">
                            {{ __('Cancel Subscription') }}
                        </button>
                    </x-slot>

                    <x-slot name="modalTitle">
                        {{ __('Are you sure you want to cancel') }}?<br>
                        {{ __('Your subscription will not be renewed.') }}
                    </x-slot>

                    <x-slot name="content">

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

						<span class="ml-2 mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
							<button type="button" @click="on = false"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
							    {{ __('Keep Subscription') }}
							</button>
						</span>

                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
							<a href="{{ url('app/subscription/cancel') }}"
                               class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
							    {{ __('Cancel Subscription') }}
							</a>
						</span>
                        </div>

                    </x-slot>
                </x-modal>
{{--            @endif--}}


{{--            @if (auth()->user()->tenant->isOnGracePeriod())--}}
                <x-modal>
                    <x-slot name="trigger">
                        <button @click="on = true"
                                class="mt-2 px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-indigo-700 transition ease-in-out duration-150">
                            {{ __('Resume Subscription') }}
                        </button>
                    </x-slot>

                    <x-slot name="modalTitle">
                        {{ __('Are you sure you want to resume this subscription') }}?<br>
                        {{ __('Your subscription will continue to be renewed.') }}
                    </x-slot>

                    <x-slot name="content">

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

							<span class="ml-2 mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
								<button type="button" @click="on = false"
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
								    {{ __('Cancel Subscription') }}
								</button>
							</span>

                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
								<a href="{{ url('app/subscription/resume') }}"
                                   class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:border-green-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
								    {{ __('Resume Subscription') }}
								</a>
							</span>
                        </div>

                    </x-slot>

                </x-modal>
{{--            @endif--}}

        </div>

    </div>

    @if (auth()->user()->tenant->isOnGracePeriod() === false && auth()->user()->tenant->status !== 'active' && auth()->user()->tenant->status !== 'lifetime')
        <div class="max-w-md mx-auto space-y-4 lg:max-w-5xl lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0">
        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                <div>
                    <h3 class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-blue-100 text-blue-600"
                        id="tier-standard">
                        Monthly
                    </h3>
                </div>
                <div class="mt-4 flex items-baseline text-6xl text-blue-600 font-extrabold">
                    $9
                    <span class="ml-1 text-2xl font-medium text-blue-600 text-gray-500">
                  /month
                </span>
                </div>
            </div>
            <div class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Unlimited domains
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Automatic DNS updates
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Domain renewal reminders
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Unlimited users
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Cancel anytime
                        </p>
                    </li>
                </ul>
                <div class="rounded-md shadow">

                    <a href="{{ route('subscribe', 'monthly') }}" type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Subscribe
                    </a>

                </div>
            </div>
        </div>

        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                <div>
                    <h3 class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-blue-100 text-blue-600"
                        id="tier-standard">
                        Annually
                    </h3>
                </div>
                <div class="mt-4 flex items-baseline text-6xl text-blue-600 font-extrabold">
                    $99
                    <span class="ml-1 text-2xl font-medium text-blue-600 text-gray-500">
                  /year (1 Month Free)
                </span>
                </div>
            </div>
            <div class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Unlimited domains
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Automatic DNS updates
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Domain renewal reminders
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Unlimited users
                        </p>
                    </li>

                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check -->
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="ml-3 text-base text-gray-700">
                            Cancel anytime
                        </p>
                    </li>
                </ul>
                <div class="rounded-md shadow">
                    <a href="{{ route('subscribe', 'annually') }}" type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Subscribe
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
