<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareTurnstile implements ValidationRule
{
    /**
     * Validate a Cloudflare Turnstile response token.
     *
     * @see https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.cloudflare.turnstile_secret');

        // If Turnstile is not configured, skip validation (local dev)
        if (empty($secret)) {
            return;
        }

        // If no token was submitted, fail
        if (empty($value)) {
            $fail('Please complete the security challenge.');

            return;
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! $response->json('success')) {
                Log::warning('Cloudflare Turnstile verification failed', [
                    'ip' => request()->ip(),
                    'errors' => $response->json('error-codes', []),
                ]);
                $fail('Security verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Cloudflare Turnstile request failed', ['error' => $e->getMessage()]);
            // Don't block the user if Turnstile API is unreachable
        }
    }
}
