<?php

namespace App\Support;

use App\Models\SeoPage;

class SeoManager
{
    public function resolve(string $pageType, string $pageKey, array $defaults = []): array
    {
        $siteName = (string) SiteSettings::get('site_name', config('app.name'));
        $siteDescription = (string) SiteSettings::get('site_tagline', 'A premium product studio portfolio platform.');
        $defaultOgImage = (string) SiteSettings::get('default_og_image', '');

        $configured = SeoPage::query()
            ->where('page_type', $pageType)
            ->where('page_key', $pageKey)
            ->first();

        $title = $configured?->meta_title ?: ($defaults['title'] ?? $siteName);
        $description = $configured?->meta_description ?: ($defaults['description'] ?? $siteDescription);

        return [
            'title' => $title,
            'description' => $description,
            'og_title' => $configured?->og_title ?: $title,
            'og_description' => $configured?->og_description ?: $description,
            'og_image' => $configured?->og_image ?: ($defaults['og_image'] ?? $defaultOgImage),
            'canonical' => $configured?->canonical_url ?: ($defaults['canonical'] ?? request()->fullUrl()),
            'robots' => $configured?->robots_directive ?: ($defaults['robots'] ?? 'index,follow'),
            'schema' => $configured?->schema_markup,
            'site_name' => $siteName,
        ];
    }
}
