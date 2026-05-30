<?php

namespace App\Rules;

use App\Services\Captcha as CaptchaService;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Reusable CAPTCHA validation rule.
 *
 * Drop it on the `captcha` field of any form that renders <x-captcha />:
 *
 *     $request->validate([
 *         // ...
 *         'captcha' => ['required', new \App\Rules\Captcha],
 *     ]);
 *
 * It reads the signed token and honeypot that the component emits alongside the
 * visible answer field, so the calling code never has to know about them.
 */
class Captcha implements ValidationRule, DataAwareRule
{
    /** All data under validation (gives access to the hidden token + honeypot). */
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Honeypot: a hidden field no human ever fills. If it has content, a bot
        // walked the DOM and filled every input — reject silently-ish.
        if (! empty($this->data['_captcha_hp'] ?? null)) {
            $fail('Verification failed.');

            return;
        }

        $token = $this->data['_captcha_token'] ?? null;

        if (! CaptchaService::verify(
            is_string($value) ? $value : null,
            is_string($token) ? $token : null
        )) {
            $fail('Incorrect code.');
        }
    }
}
