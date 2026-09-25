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

            // Content-Security-Policy — locks down script/style/img/font origins.
            // googleapis + gstatic: reCAPTCHA v3. cdnjs: Font Awesome.
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

        return $response;
    }
}
