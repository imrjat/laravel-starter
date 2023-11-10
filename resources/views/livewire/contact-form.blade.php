<div>
    @if($successMessage)
        <div class="alert alert-green">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="submitForm" class="grid grid-cols-1 row-gap-6 sm:grid-cols-2 sm:col-gap-8">

        <div class="sm:col-span-2 mt-5">
            <label for="name" class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">Name</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="name" name="name" wire:model.live="name" type="text" value="{{ old('name') }}" class="border @error('name') border-red-500 @enderror text-gray-900 py-3 px-4 block w-full transition ease-in-out duration-150"/>
            </div>
            @error('name')
            <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2 mt-5">
            <label for="email"
                   class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">Email</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="email" name="email" wire:model.live="email" type="email" value="{{ old('email') }}"
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
                <textarea id="message" wire:model.live="message" name="message" rows="4"
                          class="border @error('name') border-red-500 @enderror text-gray-900 py-3 px-4 block w-full transition ease-in-out duration-150">{{ old('message') }}</textarea>
            </div>
            @error('message')
            <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2 mt-5">
                <span class="w-full inline-flex rounded-md">
                    <button type="submit"
                            class="inline-flex items-center justify-center py-3 px-6 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out disabled:opacity-50">
                        <span>Send Email</span>
                    </button>
                </span>
        </div>
    </form>

</div>
