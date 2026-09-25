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
        // Behind the Cloudflare tunnel, TLS terminates at the edge and the origin
        // sees plain HTTP — so Laravel/Livewire generate http:// URLs, which the
        // browser then refuses to call from an https:// page (CSP connect-src 'self'
        // is scheme-sensitive). Force https URL generation whenever APP_URL is https.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

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
