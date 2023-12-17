<div>

    @if($tenant->isActive())

        <h1>{{ __('Subscription') }}</h1>

        <div class="card">

            <div class="well dark:text-gray-300">

                @if($tenant->isActive())
                    <b>{{ __('Your Plan') }}:</b> {{ $plan }}<br>
                    <b>{{ __('Account Status') }}:</b> {{ $tenant->stripe_status }}<br>
                    <b>{{ __('Next Payment') }}:</b> {{ $tenant->ends_at->format('jS F Y') }}<br>
                    <b>{{ __('Payment method') }}:</b> {{ __('Card') }} ({{ $tenant->card_brand }} {{ __('ending') }} {{ $tenant->card_last_four }})<br>
                @endif

                @if ($tenant->cancel_at_period_end === 'Yes' || $tenant->isOnGracePeriod())
                    <p>{{ __('Cancel Scheduled for') }} {{ $tenant->ends_at->format('jS M Y H:i A') }}</p>
                @endif

                <p><a href="{{ route('billing-portal') }}" class="btn btn-primary">{{ __('Manage Subscription') }}</a></p>

            </div>

        </div>
    @endif

    @if ($tenant->stripe_status == 'Trial Expired')
        <div class="alert alert-primary">
            {{ __('Your trial has ended, please subscribe to continue.') }}
        </div>
    @endif

    @if(! $tenant->isActive())
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="ml-3 text-base text-gray-700">
                                Cancel anytime
                            </p>
                        </li>
                    </ul>

                    <x-form method="post" action="{{ route('admin.billing.subscribe') }}">
                        <input type="hidden" name="type" value="monthly">
                        <x-button class="flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" aria-describedby="tier-standard">
                            Subscribe
                        </x-button>
                    </x-form>

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

                    <x-form method="post" action="{{ route('admin.billing.subscribe') }}">
                        <input type="hidden" name="type" value="annually">
                        <x-button class="flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" aria-describedby="tier-standard">
                            Subscribe
                        </x-button>
                    </x-form>

                </div>
            </div>
        </div>
    @endif

</div>
