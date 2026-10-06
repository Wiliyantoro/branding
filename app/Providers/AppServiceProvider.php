<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\RecaptchaService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

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

        // --- HtmlSanitizer: allow structured formatting tags used by blog content ---
        // Symfony default strips <ul>/<li>/<h3>/<a> because the default strategy is the
        // minimal "basic" allowlist. Re-bind a config that keeps our rich-text tags but
        // still blocks XSS (no <script>, no inline event handlers, no javascript: URIs).
        // ponytail: only extend the static element allowlist — drop this block once
        // HtmlSanitizerConfig gains a config('html-sanitizer') YAML wrapper.
        $this->app->singleton(HtmlSanitizerInterface::class, function () {
            $config = (new HtmlSanitizerConfig())
                ->allowSafeElements()   // W3C safe-allowlist (strip dangerous tag+attrs)
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->allowMediaSchemes(['http', 'https', 'data'])
                ->allowRelativeLinks()
                ->allowRelativeMedias();

            // tags that survive allowSafeElements but need structural allowance for blog
            foreach (['h3', 'ul', 'li', 'ol', 'blockquote', 'code', 'pre', 'br', 'span', 'div'] as $tag) {
                $config = $config->allowElement($tag);
            }

            // Keep hyperlinks on blog content (nav prev/next, external links).
            // NOTE: must be applied AFTER allowElement() — calling allowElement('a')
            // later resets <a>'s allowed attributes and drops href.
            $config = $config->allowAttribute('href', 'a');

            return new HtmlSanitizer($config);
        });
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
