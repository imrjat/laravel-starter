<div>

    @if($successMessage)
        <div class="rounded-md bg-green-50 p-4 mt-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-green-800">
                        {{ $successMessage }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button
                                type="button"
                                wire:click="$set('successMessage', null)"
                                class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150"
                                aria-label="Dismiss">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submitForm" class="grid grid-cols-1 row-gap-6 sm:grid-cols-2 sm:col-gap-8">


        <div class="sm:col-span-2 mt-5">
            <label for="name" class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">Name</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="name" name="name" wire:model="name" type="text" value="{{ old('name') }}"
                       class="border @error('name') border-red-500 @enderror text-gray-900 py-3 px-4 block w-full transition ease-in-out duration-150"/>
            </div>
            @error('name')
            <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2 mt-5">
            <label for="email"
                   class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">Email</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="email" name="email" wire:model="email" type="email" value="{{ old('email') }}"
                       class="border @error('name') border-red-500 @enderror text-gray-900 py-3 px-4 block w-full transition ease-in-out duration-150"/>
            </div>
            @error('email')
            <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2 mt-5">
            <label for="message"
                   class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">Message</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <textarea id="message" wire:model="message" name="message" rows="4"
                          class="border @error('name') border-red-500 @enderror text-gray-900 py-3 px-4 block w-full transition ease-in-out duration-150">{{ old('message') }}</textarea>
            </div>
            @error('message')
            <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2 mt-5">
          <span class="w-full inline-flex rounded-md shadow-sm">
              <button type="submit"
                      class="inline-flex items-center justify-center py-3 px-6 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out disabled:opacity-50">
                <span>Send Email</span>
              </button>
          </span>
        </div>
    </form>
</div>
