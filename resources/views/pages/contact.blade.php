@extends('layouts.site')

@section('content')
@php
    $personName = $siteSettings['brand_person_name'] ?? ($siteSettings['site_name'] ?? 'me');
    $contactEmail = $siteSettings['contact_email'] ?? null;
    $captchaEnabled = (bool) config('services.captcha.enabled', false);
    $captchaProvider = (string) config('services.captcha.provider', 'hcaptcha');
    $hcaptchaSiteKey = (string) config('services.captcha.hcaptcha.site_key', '');
    $recaptchaSiteKey = (string) config('services.captcha.recaptcha.site_key', '');
    $captchaConfigured = $captchaEnabled
        && (($captchaProvider === 'hcaptcha' && filled($hcaptchaSiteKey))
            || ($captchaProvider === 'recaptcha' && filled($recaptchaSiteKey)));
@endphp

<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">Contact</p>
        <h1 class="section-title">Let's build something meaningful</h1>
        <p class="text-brand-muted mt-3 max-w-2xl">Share your goals and {{ $personName }} will get back with a clear plan.</p>
        @if(filled($contactEmail))
            <p class="text-sm mt-3">Direct email: <a href="mailto:{{ $contactEmail }}" class="text-brand-accent">{{ $contactEmail }}</a></p>
        @endif
    </div>

    <div class="glass-card p-6 md:p-8 mt-8 max-w-3xl">
        <form action="{{ route('contact.submit') }}" method="POST" class="grid md:grid-cols-2 gap-4">
            @csrf

            <div class="md:col-span-1">
                <label class="form-label" for="full_name">Full Name</label>
                <input class="form-input" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-1">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label" for="phone">Phone</label>
                <input class="form-input" id="phone" name="phone" value="{{ old('phone') }}">
            </div>

            <div>
                <label class="form-label" for="company">Company</label>
                <input class="form-input" id="company" name="company" value="{{ old('company') }}">
            </div>

            <div>
                <label class="form-label" for="service_interest">Service Interest</label>
                <input class="form-input" id="service_interest" name="service_interest" value="{{ old('service_interest') }}" placeholder="Web platform, SEO content, CRM...">
            </div>

            <div>
                <label class="form-label" for="budget">Budget Range</label>
                <input class="form-input" id="budget" name="budget" value="{{ old('budget') }}" placeholder="$3k-$8k">
            </div>

            <div class="md:col-span-2">
                <label class="form-label" for="project_timeline">Timeline</label>
                <input class="form-input" id="project_timeline" name="project_timeline" value="{{ old('project_timeline') }}" placeholder="4-6 weeks">
            </div>

            <div class="md:col-span-2">
                <label class="form-label" for="message">Project Brief</label>
                <textarea class="form-input min-h-36" id="message" name="message" required>{{ old('message') }}</textarea>
                @error('message') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">
            <input type="hidden" name="captcha_token" id="captcha_token" value="{{ old('captcha_token') }}">
            @error('captcha_token') <p class="form-error md:col-span-2">{{ $message }}</p> @enderror

            @if($captchaEnabled && ! $captchaConfigured)
                <p class="form-error md:col-span-2">Captcha is enabled but not configured. Please contact us directly using the email above.</p>
            @endif

            @if($captchaEnabled && $captchaProvider === 'hcaptcha' && $captchaConfigured)
                <div class="md:col-span-2">
                    <div
                        class="h-captcha"
                        data-sitekey="{{ $hcaptchaSiteKey }}"
                        data-callback="setCaptchaToken"
                        data-expired-callback="clearCaptchaToken"
                        data-error-callback="clearCaptchaToken"
                    ></div>
                </div>
                <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
            @endif

            @if($captchaEnabled && $captchaProvider === 'recaptcha' && $captchaConfigured)
                <div class="md:col-span-2">
                    <div
                        class="g-recaptcha"
                        data-sitekey="{{ $recaptchaSiteKey }}"
                        data-callback="setCaptchaToken"
                        data-expired-callback="clearCaptchaToken"
                        data-error-callback="clearCaptchaToken"
                    ></div>
                </div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            @endif

            @if($captchaEnabled && $captchaConfigured)
                <script>
                    function setCaptchaToken(token) {
                        document.getElementById('captcha_token').value = token;
                    }

                    function clearCaptchaToken() {
                        document.getElementById('captcha_token').value = '';
                    }
                </script>
            @endif

            <div class="md:col-span-2 mt-2">
                <button type="submit" class="btn-primary w-full md:w-auto" @disabled($captchaEnabled && ! $captchaConfigured)>Submit Inquiry</button>
            </div>
        </form>
    </div>
</section>
@endsection
