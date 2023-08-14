<?php

use App\Livewire\Admin\Settings\ApplicationLogo;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->authenticate();
});

test('throws Exception when a none image is selected', function () {
    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogo', 'Demo.txt')
        ->call('update');
})->throws(Exception::class);

test('can upload file', function () {

    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogo', $file)
        ->set('applicationLogoDark', $file)
        ->call('update')
        ->assertValid();

    $path = Setting::where('key', 'applicationLogo')->value('value');
    Storage::disk('public')->assertExists($path);

    $path = Setting::where('key', 'applicationLogoDark')->value('value');
    Storage::disk('public')->assertExists($path);
});

test('existing file is deleted', function () {

    Storage::fake('public');

    Setting::create([
        'tenant_id' => auth()->user()->tenant_id,
        'key' => 'applicationLogo',
        'value' => 'avatar.png',
    ]);

    $path = Setting::where('key', 'applicationLogo')->value('value');

    Storage::disk('public')->delete($path);
    Storage::disk('public')->assertMissing($path);

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogo', $file)
        ->set('applicationLogoDark', $file)
        ->call('update')
        ->assertValid();

    $path = Setting::where('key', 'applicationLogo')->value('value');
    Storage::disk('public')->assertExists($path);

    $path = Setting::where('key', 'applicationLogoDark')->value('value');
    Storage::disk('public')->assertExists($path);
});

test('existing file is deleted dark', function () {

    Storage::fake('public');

    Setting::create([
        'tenant_id' => auth()->user()->tenant_id,
        'key' => 'applicationLogoDark',
        'value' => 'avatar.png',
    ]);

    $path = Setting::where('key', 'applicationLogoDark')->value('value');

    Storage::disk('public')->delete($path);
    Storage::disk('public')->assertMissing($path);

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogoDark', $file)
        ->call('update')
        ->assertValid();
    
    $path = Setting::where('key', 'applicationLogoDark')->value('value');
    Storage::disk('public')->assertExists($path);
});
