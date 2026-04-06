@extends('layouts.site')

@section('content')
@php
    $personName = $siteSettings['brand_person_name'] ?? ($siteSettings['site_name'] ?? 'Your Name');
    $personRole = $siteSettings['brand_role'] ?? 'Personal Brand Portfolio';
    $shortBio = $siteSettings['brand_short_bio'] ?? 'I build practical web products and showcase real execution through projects and content.';
    $profilePhoto = $siteSettings['profile_photo_url'] ?? '';
    $socialLinks = [
        ['label' => 'LinkedIn', 'url' => $siteSettings['social_linkedin_url'] ?? null],
        ['label' => 'GitHub', 'url' => $siteSettings['social_github_url'] ?? null],
        ['label' => 'X', 'url' => $siteSettings['social_x_url'] ?? null],
    ];
@endphp

<section class="container-main mb-12">
    <div class="hero-frame">
        <div class="hero-orb hero-orb-left"></div>
        <div class="hero-orb hero-orb-right"></div>

        <div class="grid lg:grid-cols-2 gap-10 items-center relative z-10">
            <div>
                <p class="eyebrow">{{ $personRole }}</p>
                <h1 class="hero-title mt-4">{{ $personName }} - Portfolio & Projects</h1>
                <p class="hero-subtitle mt-5">
                    {{ $shortBio }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('case-studies.index') }}" class="btn-primary">View Projects</a>
                    <a href="{{ route('contact.show') }}" class="btn-secondary">Contact Me</a>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="metric-box">
                        <p class="metric-value">{{ $featuredProjects->count() }}+</p>
                        <p class="metric-label">Projects</p>
                    </div>
                    <div class="metric-box">
                        <p class="metric-value">{{ $services->count() }}+</p>
                        <p class="metric-label">Service Tracks</p>
                    </div>
                    <div class="metric-box">
                        <p class="metric-value">{{ $latestPosts->count() }}+</p>
                        <p class="metric-label">Articles</p>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 md:p-8">
                <div class="flex items-center gap-4">
                    @if(filled($profilePhoto))
                        <img src="{{ $profilePhoto }}" alt="{{ $personName }} profile photo" class="h-16 w-16 rounded-xl object-cover border border-brand-border">
                    @else
                        <div class="h-16 w-16 rounded-xl border border-brand-border bg-slate-900/60 flex items-center justify-center font-display text-2xl">
                            {{ strtoupper(substr($personName, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-display text-xl">{{ $personName }}</p>
                        <p class="text-sm text-brand-muted">{{ $personRole }}</p>
                    </div>
                </div>

                <p class="text-brand-muted text-sm mt-5">Platform highlights</p>
                <ul class="space-y-3 mt-4 text-sm">
                    <li class="stack-item">Projects + blog + services in one system</li>
                    <li class="stack-item">Laravel 12 + Filament admin workflow</li>
                    <li class="stack-item">Lead capture pipeline and follow-up tracking</li>
                    <li class="stack-item">SEO controls, sitemap, robots and analytics</li>
                </ul>

                <div class="flex flex-wrap gap-2 mt-5">
                    @foreach($socialLinks as $social)
                        @if(filled($social['url']))
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="badge">{{ $social['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container-main mb-12">
    <div class="section-head">
        <p class="eyebrow">Services</p>
        <h2 class="section-title">How I can help</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-5 mt-6">
        @forelse($services as $service)
            <article class="glass-card p-5 card-hover">
                <p class="text-xs uppercase tracking-widest text-brand-muted">{{ $service->icon ?: 'Core' }}</p>
                <h3 class="font-display text-xl mt-3">{{ $service->title }}</h3>
                <p class="text-sm text-brand-muted mt-2">{{ $service->excerpt ?: str($service->description)->limit(110) }}</p>
            </article>
        @empty
            <p class="text-brand-muted">Add services from the admin panel to showcase offerings.</p>
        @endforelse
    </div>
</section>

<section class="container-main mb-12">
    @php
        $projectTypes = $featuredProjects
            ->pluck('industry')
            ->filter(fn ($type) => filled($type))
            ->map(fn ($type) => (string) $type)
            ->unique()
            ->values();
    @endphp

    <div class="section-head">
        <p class="eyebrow">Projects</p>
        <h2 class="section-title">Featured work with outcomes</h2>
    </div>

    @if($projectTypes->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <a href="{{ route('case-studies.index') }}" class="glass-card p-4 card-hover">
                <p class="text-xs uppercase tracking-widest text-brand-muted">All Types</p>
                <p class="font-display text-lg mt-2">Browse everything</p>
                <p class="text-sm text-brand-muted mt-2">{{ $featuredProjects->count() }} projects</p>
            </a>
            @foreach($projectTypes as $projectType)
                <a href="{{ route('case-studies.index', ['type' => \Illuminate\Support\Str::slug($projectType)]) }}" class="glass-card p-4 card-hover">
                    <p class="text-xs uppercase tracking-widest text-brand-muted">Type</p>
                    <p class="font-display text-lg mt-2">{{ $projectType }}</p>
                    <p class="text-sm text-brand-muted mt-2">{{ $featuredProjects->where('industry', $projectType)->count() }} projects</p>
                </a>
            @endforeach
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-5 mt-6">
        @forelse($featuredProjects as $project)
            <article class="glass-card p-5 card-hover">
                @if($project->cover_image_url)
                    <img
                        src="{{ $project->cover_image_url }}"
                        alt="{{ $project->title }} cover image"
                        class="h-44 w-full object-cover rounded-xl border border-brand-border"
                        loading="lazy"
                    >
                @else
                    <div class="h-44 w-full rounded-xl border border-brand-border bg-slate-900/40 flex items-center justify-center text-xs uppercase tracking-widest text-brand-muted">
                        No Cover Image
                    </div>
                @endif
                <p class="text-xs text-brand-muted uppercase tracking-widest mt-4">{{ $project->industry ?: 'General' }}</p>
                <h3 class="font-display text-xl mt-2">{{ $project->title }}</h3>
                <p class="text-sm text-brand-muted mt-2">{{ $project->summary }}</p>
                <a href="{{ route('case-studies.show', $project->slug) }}" class="text-sm mt-4 inline-flex text-brand-accent">Read Story -></a>
            </article>
        @empty
            <p class="text-brand-muted">Publish projects from admin to populate this section.</p>
        @endforelse
    </div>

    <div class="mt-6">
        <a href="{{ route('case-studies.index') }}" class="btn-secondary">Explore Full Project Grid</a>
    </div>
</section>

<section class="container-main mb-12">
    <div class="section-head">
        <p class="eyebrow">Process</p>
        <h2 class="section-title">How I work</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-5 mt-6">
        <div class="glass-card p-5"><h3 class="font-display text-lg">1. Discover</h3><p class="text-sm text-brand-muted mt-2">Understand your goal, audience, and project scope.</p></div>
        <div class="glass-card p-5"><h3 class="font-display text-lg">2. Build</h3><p class="text-sm text-brand-muted mt-2">Design and ship with clean Laravel architecture.</p></div>
        <div class="glass-card p-5"><h3 class="font-display text-lg">3. Improve</h3><p class="text-sm text-brand-muted mt-2">Use SEO and data to keep improving performance.</p></div>
    </div>
</section>

<section class="container-main mb-12">
    <div class="section-head">
        <p class="eyebrow">Testimonials</p>
        <h2 class="section-title">What people say</h2>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mt-6">
        @forelse($testimonials as $testimonial)
            <article class="glass-card p-5">
                <p class="text-sm leading-relaxed">"{{ $testimonial->quote }}"</p>
                <div class="flex items-center gap-3 mt-4">
                    @if(filled($testimonial->avatar))
                        <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }} avatar" class="h-9 w-9 rounded-full object-cover border border-brand-border">
                    @endif
                    <p class="text-sm text-brand-muted">{{ $testimonial->name }} @if($testimonial->company) - {{ $testimonial->company }} @endif</p>
                </div>
            </article>
        @empty
            <p class="text-brand-muted">Add testimonials to increase trust signals.</p>
        @endforelse
    </div>
</section>

<section class="container-main mb-12">
    <div class="section-head">
        <p class="eyebrow">Latest Posts</p>
        <h2 class="section-title">Knowledge and updates</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-5 mt-6">
        @forelse($latestPosts as $post)
            <article class="glass-card p-5 card-hover">
                <p class="text-xs text-brand-muted uppercase tracking-widest">{{ $post->reading_time_minutes }} min read</p>
                <h3 class="font-display text-lg mt-2">{{ $post->title }}</h3>
                <p class="text-sm text-brand-muted mt-2">{{ $post->excerpt ?: str(strip_tags($post->content))->limit(90) }}</p>
                <a href="{{ route('blog.show', $post->slug) }}" class="text-sm mt-4 inline-flex text-brand-accent">Read Article -></a>
            </article>
        @empty
            <p class="text-brand-muted">No posts yet. Publish content from admin.</p>
        @endforelse
    </div>
</section>

@if($faqs->isNotEmpty())
<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">FAQ</p>
        <h2 class="section-title">Common questions</h2>
    </div>

    <div class="grid gap-4 mt-6">
        @foreach($faqs as $faq)
            <article class="glass-card p-5">
                <h3 class="font-display text-lg">{{ $faq->question }}</h3>
                <p class="text-sm text-brand-muted mt-2">{{ $faq->answer }}</p>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection
