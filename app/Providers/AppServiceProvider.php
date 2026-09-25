<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\RecaptchaService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // reCAPTCHA v3 verifier — disabled automatically when keys are absent.
        $this->app->singleton(RecaptchaService::class, fn () => new RecaptchaService(
            secretKey: config('recaptcha.secret_key'),
            minScore: (float) config('recaptcha.min_score'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-detect base URL from current request — overrides APP_URL so the
        // same container/instance works with any domain it receives traffic from.
        // This is useful for containerized deployments serving multiple domains.
        // Uncomment if you want to disable and always use APP_URL from .env
        // $this->app['config']->set('app.url', \Illuminate\Support\Facades\Request::schemeAndHttpDomain());

        // Site identity + socials come from the settings table, not hardcoded markup.
        // Wildcards keep future public views covered without touching this list.
        View::composer(['layouts.app', 'partials.*', 'welcome', 'blog.*'], function ($view) {
            $settings = Schema::hasTable('settings') ? Setting::map() : [];

            $view->with([
                'siteName' => $settings['site_name'] ?? config('app.name'),
                'siteTagline' => $settings['site_tagline'] ?? '',
                'siteDescription' => $settings['site_description'] ?? '',
                'siteEmail' => $settings['email'] ?? null,
                'siteLocation' => $settings['location'] ?? null,
                'footerText' => $settings['footer_text'] ?? null,
                'metaKeywords' => $settings['meta_keywords'] ?? null,
                'ogImage' => $settings['og_image'] ?? null,
                'faviconSetting' => $settings['favicon'] ?? null,
                'socials' => array_filter([
                    'github' => $settings['github'] ?? null,
                    'linkedin' => $settings['linkedin'] ?? null,
                    'twitter' => $settings['twitter'] ?? null,
                    'instagram' => $settings['instagram'] ?? null,
                ]),
            ]);
        });
    }
}
