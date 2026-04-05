<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\SeoPage;
use App\Models\Service;
use Illuminate\Support\Str;

class LaunchReadinessChecker
{
    /**
     * @return array{
     *   score: int,
     *   summary: array{total: int, pass: int, warn: int, fail: int},
     *   checks: array<int, array{key: string, label: string, status: string, message: string}>
     * }
     */
    public function build(): array
    {
        $checks = [
            $this->brandProfileCheck(),
            $this->contactEmailCheck(),
            $this->socialLinksCheck(),
            $this->servicesContentCheck(),
            $this->projectsContentCheck(),
            $this->blogContentCheck(),
            $this->seoCoverageCheck(),
            $this->appUrlCheck(),
            $this->driversCheck(),
            $this->mailCheck(),
            $this->captchaCheck(),
            $this->analyticsCheck(),
            $this->sitemapCheck(),
        ];

        $summary = [
            'total' => count($checks),
            'pass' => collect($checks)->where('status', 'pass')->count(),
            'warn' => collect($checks)->where('status', 'warn')->count(),
            'fail' => collect($checks)->where('status', 'fail')->count(),
        ];

        $weightedScore = (($summary['pass'] + ($summary['warn'] * 0.5)) / max($summary['total'], 1)) * 100;

        return [
            'score' => (int) round($weightedScore),
            'summary' => $summary,
            'checks' => $checks,
        ];
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function brandProfileCheck(): array
    {
        $required = [
            'site_name',
            'brand_person_name',
            'brand_role',
            'brand_short_bio',
            'brand_long_bio',
        ];

        $placeholders = [
            'brand_person_name' => ['Your Name'],
            'brand_role' => ['Laravel Developer & Product Engineer'],
            'brand_short_bio' => ['I build high-performing portfolio and product websites that help personal brands turn visitors into clients.'],
        ];

        $missing = [];
        $placeholderKeys = [];

        foreach ($required as $key) {
            $value = trim((string) SiteSettings::get($key, ''));

            if ($value === '') {
                $missing[] = $key;

                continue;
            }

            foreach ($placeholders[$key] ?? [] as $placeholder) {
                if (Str::lower($value) === Str::lower($placeholder)) {
                    $placeholderKeys[] = $key;
                }
            }
        }

        if ($missing !== []) {
            return $this->check(
                'brand_profile',
                'Brand profile details',
                'fail',
                'Missing: '.implode(', ', $missing)
            );
        }

        if ($placeholderKeys !== []) {
            return $this->check(
                'brand_profile',
                'Brand profile details',
                'warn',
                'Still using placeholder values for: '.implode(', ', array_unique($placeholderKeys))
            );
        }

        return $this->check('brand_profile', 'Brand profile details', 'pass', 'Core personal brand fields are complete.');
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function contactEmailCheck(): array
    {
        $email = trim((string) SiteSettings::get('contact_email', ''));

        if ($email === '') {
            return $this->check('contact_email', 'Public contact email', 'fail', 'Add contact_email in Brand Profile.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->check('contact_email', 'Public contact email', 'fail', "Invalid email format: {$email}");
        }

        return $this->check('contact_email', 'Public contact email', 'pass', $email);
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function socialLinksCheck(): array
    {
        $keys = ['social_linkedin_url', 'social_github_url', 'social_x_url'];
        $validCount = 0;

        foreach ($keys as $key) {
            $value = trim((string) SiteSettings::get($key, ''));

            if ($value !== '' && filter_var($value, FILTER_VALIDATE_URL)) {
                $validCount++;
            }
        }

        if ($validCount === 0) {
            return $this->check('social_links', 'Social links', 'warn', 'No valid social profile URLs configured yet.');
        }

        if ($validCount < 2) {
            return $this->check('social_links', 'Social links', 'warn', "Only {$validCount} social link configured.");
        }

        return $this->check('social_links', 'Social links', 'pass', "{$validCount} social links configured.");
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function servicesContentCheck(): array
    {
        $count = Service::published()->count();

        return $this->countBasedCheck('services_content', 'Published services', $count, 3);
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function projectsContentCheck(): array
    {
        $count = Project::published()->count();

        return $this->countBasedCheck('projects_content', 'Published projects', $count, 3);
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function blogContentCheck(): array
    {
        $count = BlogPost::published()->count();

        return $this->countBasedCheck('blog_content', 'Published blog posts', $count, 3);
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function seoCoverageCheck(): array
    {
        $requiredStaticPages = ['home', 'services', 'case-studies', 'blog', 'about', 'contact'];

        $configured = SeoPage::query()
            ->where('page_type', 'static')
            ->whereIn('page_key', $requiredStaticPages)
            ->distinct('page_key')
            ->count('page_key');

        if ($configured === count($requiredStaticPages)) {
            return $this->check('seo_coverage', 'SEO static page coverage', 'pass', 'All static pages have SEO entries.');
        }

        if ($configured === 0) {
            return $this->check('seo_coverage', 'SEO static page coverage', 'fail', 'No static SEO page entries found.');
        }

        return $this->check(
            'seo_coverage',
            'SEO static page coverage',
            'warn',
            "{$configured}/".count($requiredStaticPages).' configured.'
        );
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function appUrlCheck(): array
    {
        $url = (string) config('app.url');

        if (blank($url)) {
            return $this->check('app_url', 'Production APP_URL', 'fail', 'APP_URL is empty.');
        }

        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1'], true) || Str::endsWith($host, '.test');
        $isHttps = Str::startsWith($url, 'https://');

        if ($isLocalHost) {
            return $this->check('app_url', 'Production APP_URL', 'fail', "Still local URL: {$url}");
        }

        if (! $isHttps) {
            return $this->check('app_url', 'Production APP_URL', 'warn', "Use HTTPS in production: {$url}");
        }

        return $this->check('app_url', 'Production APP_URL', 'pass', $url);
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function driversCheck(): array
    {
        $queue = (string) config('queue.default');
        $session = (string) config('session.driver');
        $cache = (string) config('cache.default');

        $isRecommended = $queue === 'database' && $session === 'database' && $cache === 'file';
        $message = "queue={$queue}, session={$session}, cache={$cache}";

        if ($isRecommended) {
            return $this->check('runtime_drivers', 'Shared-host runtime drivers', 'pass', $message);
        }

        return $this->check('runtime_drivers', 'Shared-host runtime drivers', 'warn', "Recommended: queue=database, session=database, cache=file ({$message})");
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function mailCheck(): array
    {
        $mailer = (string) config('mail.default');
        $from = (string) config('mail.from.address');
        $smtpHost = (string) config('mail.mailers.smtp.host');

        if ($mailer === 'log') {
            return $this->check('mail_delivery', 'Mail delivery setup', 'fail', 'MAIL_MAILER is set to log.');
        }

        if (blank($from)) {
            return $this->check('mail_delivery', 'Mail delivery setup', 'fail', 'MAIL_FROM_ADDRESS is missing.');
        }

        if ($mailer === 'smtp' && blank($smtpHost)) {
            return $this->check('mail_delivery', 'Mail delivery setup', 'fail', 'SMTP mailer selected but MAIL_HOST is empty.');
        }

        return $this->check('mail_delivery', 'Mail delivery setup', 'pass', "mailer={$mailer}, from={$from}");
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function captchaCheck(): array
    {
        $enabled = (bool) config('services.captcha.enabled', false);

        if (! $enabled) {
            return $this->check('captcha', 'Contact form captcha', 'warn', 'Captcha is disabled.');
        }

        $provider = (string) config('services.captcha.provider', 'hcaptcha');
        $siteKey = (string) config("services.captcha.{$provider}.site_key");
        $secret = (string) config("services.captcha.{$provider}.secret");

        if (blank($siteKey) || blank($secret)) {
            return $this->check('captcha', 'Contact form captcha', 'fail', "Captcha enabled but {$provider} keys are incomplete.");
        }

        return $this->check('captcha', 'Contact form captcha', 'pass', "{$provider} configured.");
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function analyticsCheck(): array
    {
        $gaId = trim((string) config('services.analytics.ga_measurement_id'));
        $searchConsoleToken = trim((string) config('services.analytics.search_console_verification'));

        if ($gaId !== '' && $searchConsoleToken !== '') {
            return $this->check('analytics', 'Analytics + Search Console', 'pass', 'GA4 and Search Console verification are configured.');
        }

        if ($gaId !== '' || $searchConsoleToken !== '') {
            return $this->check('analytics', 'Analytics + Search Console', 'warn', 'Only one analytics integration is configured.');
        }

        return $this->check('analytics', 'Analytics + Search Console', 'warn', 'Analytics integrations are not configured yet.');
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function sitemapCheck(): array
    {
        if (file_exists(public_path('sitemap.xml'))) {
            return $this->check('sitemap', 'Sitemap generation', 'pass', 'public/sitemap.xml exists.');
        }

        return $this->check('sitemap', 'Sitemap generation', 'warn', 'Run php artisan app:generate-sitemap.');
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function countBasedCheck(string $key, string $label, int $count, int $target): array
    {
        if ($count >= $target) {
            return $this->check($key, $label, 'pass', "{$count} published (target {$target}+).");
        }

        if ($count === 0) {
            return $this->check($key, $label, 'fail', "No published entries yet (target {$target}+).");
        }

        return $this->check($key, $label, 'warn', "{$count} published (target {$target}+).");
    }

    /**
     * @return array{key: string, label: string, status: string, message: string}
     */
    protected function check(string $key, string $label, string $status, string $message): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'status' => $status,
            'message' => $message,
        ];
    }
}
