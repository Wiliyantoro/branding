<?php

namespace App\Providers;

use App\Models\Setting;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
