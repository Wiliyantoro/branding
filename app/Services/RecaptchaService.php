<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        private readonly ?string $secretKey = null,
        private readonly float $minScore = 0.5,
    ) {}

    /**
     * Verify reCAPTCHA v3 token against Google's siteverify endpoint.
     * Returns true when verification is disabled (no keys configured),
     * so dev/test environments are not blocked by missing credentials.
     */
    public function verify(string $token, string $action = 'contact'): bool
    {
        // No keys configured — skip verification (dev/test environments).
        if (blank($this->secretKey)) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        $response = $this->callVerify($token);

        return $response->successful()
            && $response->json('success') === true
            && $response->json('action') === $action
            && (float) $response->json('score', 0) >= $this->minScore;
    }

    private function callVerify(string $token): Response
    {
        try {
            return Http::asForm()
                ->timeout(10)
                ->post(self::VERIFY_URL, [
                    'secret' => $this->secretKey,
                    'response' => $token,
                ]);
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA verification failed', ['error' => $e->getMessage()]);
            return Http::response(null, 500);
        }
    }
}
