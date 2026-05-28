<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $forceHttps = filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOL);
        $renderUrl = (string) env('RENDER_EXTERNAL_URL', '');

        if (
            $forceHttps
            || $this->app->environment('production')
            || str_starts_with((string) config('app.url'), 'https://')
            || str_starts_with($renderUrl, 'https://')
        ) {
            URL::forceScheme('https');
        }
    }
}
