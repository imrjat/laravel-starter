<?php

use App\Livewire\Admin\Settings\ApplicationLogo;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->authenticate();
});

test('throws Exception when a none image is selected', function () {
    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogo', 'Demo.txt')
        ->call('update');
})->throws(Exception::class);

test('can set name', function () {

    Storage::fake('avatars');

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(ApplicationLogo::class)
        ->set('applicationLogo', $file)
        ->call('update')
        ->assertValid();

    //Storage::disk('avatars')->assertExists('uploaded-avatar.png');
    // expect(Setting::where('key', 'app.name')->value('value'))->toBe('Demo');
});
