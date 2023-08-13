<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request, UrlGenerator $url): void
    {
        if (app()->environment() !== 'local') {
            $url->forceScheme('https');
        }

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->uncompromised();
        });

        Model::shouldBeStrict();
        //        Model::shouldBeStrict(!app()->isProduction());

        view()->composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $settings = Setting::all();
                $keys = [];

                foreach ($settings as $setting) {
                    $key = $setting->key;
                    $value = $setting->value;

                    //override config setting
                    config()->set([$key => $value]);
                }
            }
        });
    }
}
