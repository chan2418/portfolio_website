<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteName = $siteSettings['site_name'] ?? config('app.name', 'Portfolio Core');
        $personName = $siteSettings['brand_person_name'] ?? $siteName;
        $contactEmail = $siteSettings['contact_email'] ?? null;
        $footerTagline = $siteSettings['site_tagline'] ?? 'Personal brand portfolio and project platform.';
        $footerSocialLinks = [
            ['label' => 'LinkedIn', 'url' => $siteSettings['social_linkedin_url'] ?? null],
            ['label' => 'GitHub', 'url' => $siteSettings['social_github_url'] ?? null],
            ['label' => 'X', 'url' => $siteSettings['social_x_url'] ?? null],
        ];
        $seoTitle = $seo['title'] ?? $siteName;
        $seoDescription = $seo['description'] ?? ($siteSettings['site_tagline'] ?? 'Personal brand portfolio and project platform.');
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $seo['site_name'] ?? $siteName }}">
    <meta property="og:title" content="{{ $seo['og_title'] ?? $seoTitle }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? $seoDescription }}">
    @if(!empty($seo['og_image']))
        <meta property="og:image" content="{{ $seo['og_image'] }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? $seoTitle }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] ?? $seoDescription }}">
    @if(!empty($seo['og_image']))
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif

    @if(config('services.analytics.search_console_verification'))
        <meta name="google-site-verification" content="{{ config('services.analytics.search_console_verification') }}">
    @endif

    @if(!empty($seo['schema']))
        <script type="application/ld+json">{!! $seo['schema'] !!}</script>
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @php($gaId = config('services.analytics.ga_measurement_id'))
    @if($gaId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif
</head>
<body>
    <div class="site-bg-blur"></div>
    <div class="site-grid-overlay"></div>

    <header class="container-main py-6 relative z-20">
        <nav class="glass-nav flex items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="font-display text-lg tracking-wide">{{ $siteName }}</a>

            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'nav-link-active' : '' }}">Services</a>
                <a href="{{ route('case-studies.index') }}" class="nav-link {{ request()->routeIs('case-studies.*') ? 'nav-link-active' : '' }}">Projects</a>
                <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}">Blog</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
            </div>

            <a href="{{ route('contact.show') }}" class="btn-primary">Contact Me</a>
        </nav>
    </header>

    <main class="relative z-10 pb-24">
        @if(session('status'))
            <div class="container-main mb-6">
                <div class="status-banner">{{ session('status') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="container-main pb-12 relative z-10">
        <div class="glass-card p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-widest text-brand-muted">Let's connect</p>
                <h2 class="font-display text-2xl mt-2">Have a project or collaboration in mind?</h2>
                <p class="text-sm text-brand-muted mt-2">{{ $footerTagline }}</p>
            </div>
            <a href="{{ route('contact.show') }}" class="btn-secondary">Send Message</a>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-5 text-xs text-brand-muted">
            <p>(c) {{ now()->year }} {{ $personName }}.</p>
            <div class="flex items-center gap-3">
                @if(filled($contactEmail))
                    <a href="mailto:{{ $contactEmail }}" class="hover:text-white transition">{{ $contactEmail }}</a>
                @endif
                @foreach($footerSocialLinks as $link)
                    @if(filled($link['url']))
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="hover:text-white transition">{{ $link['label'] }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </footer>
</body>
</html>
