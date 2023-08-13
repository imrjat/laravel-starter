<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationSettings extends Component
{
    public string $siteName = '';
    public bool $isForced2Fa = false;

    public function mount(): void
    {
        $this->siteName = Setting::where('key', 'app.name')->value('value') ?? '';
        $this->isForced2Fa = (bool) Setting::where('key', 'is_forced_2fa')->value('value') ?? false;
    }

    public function render(): View
    {
        return view('livewire.admin.settings.application-settings');
    }

    protected function rules(): array
    {
        return [
            'siteName' => 'required|string',
        ];
    }

    protected array $messages = [
        'siteName.required' => 'Site name is required',
    ];

    /**
     * @throws ValidationException
     */
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function update(): void
    {
        $this->validate();

        Setting::updateOrCreate(['key' => 'app.name'], ['value' => $this->siteName]);
        Setting::updateOrCreate(['key' => 'is_forced_2fa'], ['value' => $this->isForced2Fa]);

        add_user_log([
            'title' => 'updated application settings',
            'link' => route('admin.settings'),
            'reference_id' => auth()->id(),
            'section' => 'Settings',
            'type' => 'Update',
        ]);

        flash('Application Settings Updated!')->success();
    }
}
