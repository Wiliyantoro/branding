<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Do not alter download responses
        if (method_exists($response, 'header')) {
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

            // Content-Security-Policy — split per area:
            //  - /admin (Filament): Alpine.js evaluates directives via new AsyncFunction(),
            //    so it requires 'unsafe-eval'; Livewire injects inline bootstrap → 'unsafe-inline'.
            //    This panel is auth-gated, so the relaxed script policy is an accepted tradeoff.
            //  - public: strict; googleapis/gstatic for reCAPTCHA v3, cdnjs for Font Awesome.
            if ($request->is('admin', 'admin/*')) {
                $response->header('Content-Security-Policy',
                    "default-src 'self'; ".
                    "script-src 'self' 'unsafe-eval' 'unsafe-inline'; ".
                    "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; ".
                    "font-src 'self' data: https://cdnjs.cloudflare.com; ".
                    "img-src 'self' data: https:; ".
                    "connect-src 'self'; ".
                    "object-src 'none'; ".
                    "base-uri 'self'; ".
                    "form-action 'self';"
                );
            } else {
                $response->header('Content-Security-Policy',
                    "default-src 'self'; ".
                    "script-src 'self' https://www.google.com https://www.gstatic.com; ".
                    "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; ".
                    "font-src 'self' https://cdnjs.cloudflare.com; ".
                    "img-src 'self' data: https:; ".
                    "connect-src 'self' https://www.google.com; ".
                    "frame-src https://www.google.com; ".
                    "object-src 'none'; ".
                    "base-uri 'self'; ".
                    "form-action 'self';"
                );
            }
        }

        return $response;
    }
}
