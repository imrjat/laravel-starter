<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StripeClient::class, function () {
            return new StripeClient(config('services.stripe.secret'));
        });
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

        view()->composer('layouts.app', function () {
            if (auth()->check()) {
                foreach (Setting::all() as $setting) {
                    //override config setting
                    config()->set([$setting->key => $setting->value]);
                }
            }
        });
    }
}
