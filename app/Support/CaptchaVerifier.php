<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class CaptchaVerifier
{
    public function enabled(): bool
    {
        return (bool) config('services.captcha.enabled', false);
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        $provider = config('services.captcha.provider', 'hcaptcha');
        $secret = (string) config("services.captcha.{$provider}.secret");

        if (blank($secret)) {
            return false;
        }

        $endpoint = $provider === 'recaptcha'
            ? 'https://www.google.com/recaptcha/api/siteverify'
            : 'https://hcaptcha.com/siteverify';

        $response = Http::asForm()->timeout(8)->post($endpoint, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        if (! $response->successful()) {
            return false;
        }

        return (bool) $response->json('success', false);
    }
}
