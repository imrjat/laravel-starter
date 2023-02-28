<div>
    <div class="card">
        <h3>{{ __('Office lockdown by IP Address') }}</h3>

        <div class="bg-primary p-2 text-gray-100 rounded">
            {{ __("When a user is set to office login only the IP's listed below will allow access.") }}
            {{ __("If you are not in the office you will not be able to login.") }}
        </div>

        <x-form wire:submit.prevent="update" method="put">

        <table>
            <tr>
                <td colspan="3" class="text-sm">{{ __('Your current IP address is') }} {{ request()->ip() }}</td>
            </tr>
            <tr>
                <th>{{ __('IP Address') }}</th>
                <th>{{ __('Comment') }}</th>
                <th></th>
            </tr>
            @foreach($ips as $index => $row)
                @error("ips.$index.ip")
                    <tr>
                        <td colspan="3">
                            <span class="text-red-600">{{ $message }}</span>
                        </td>
                    </tr>
                @enderror
                <tr>
                    <td><x-form.input wire:model="ips.{{ $index }}.ip" label="none">{{ $row['ip'] }}</x-form.input></td>
                    <td><x-form.input wire:model="ips.{{ $index }}.comment" label="none">{{ $row['comment'] }}</x-form.input></td>
                    <td class="flex justify-center pt-2"><button type="button" wire:click="remove({{ $index }})" class="text-red-600">X</button></td>
                </tr>
            @endforeach
        </table>

        <p><x-button color="blue" wire:click="add">{{ __('Add Row') }}</x-button>

        <x-button>{{ __('Save') }}</x-button>

        </x-form>

        @include('errors.messages')

    </div>
</div>