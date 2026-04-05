@extends('layouts.site')

@section('content')
@php
    $personName = $siteSettings['brand_person_name'] ?? ($siteSettings['site_name'] ?? 'Your Name');
    $personRole = $siteSettings['brand_role'] ?? 'Laravel Developer';
    $longBio = $siteSettings['brand_long_bio'] ?? 'Add your long bio from the admin settings panel.';
    $profilePhoto = $siteSettings['profile_photo_url'] ?? '';
    $contactEmail = $siteSettings['contact_email'] ?? null;
    $socialLinks = [
        ['label' => 'LinkedIn', 'url' => $siteSettings['social_linkedin_url'] ?? null],
        ['label' => 'GitHub', 'url' => $siteSettings['social_github_url'] ?? null],
        ['label' => 'X', 'url' => $siteSettings['social_x_url'] ?? null],
    ];
@endphp

<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">About</p>
        <h1 class="section-title">About {{ $personName }}</h1>
        <p class="text-brand-muted mt-3 max-w-3xl">
            {{ $longBio }}
        </p>
    </div>

    <div class="glass-card p-6 md:p-8 mt-8">
        <div class="flex flex-col md:flex-row md:items-center gap-5">
            @if(filled($profilePhoto))
                <img src="{{ $profilePhoto }}" alt="{{ $personName }} profile photo" class="h-28 w-28 rounded-2xl object-cover border border-brand-border">
            @else
                <div class="h-28 w-28 rounded-2xl border border-brand-border bg-slate-900/60 flex items-center justify-center font-display text-4xl">
                    {{ strtoupper(substr($personName, 0, 1)) }}
                </div>
            @endif

            <div>
                <h2 class="font-display text-2xl">{{ $personName }}</h2>
                <p class="text-brand-muted mt-1">{{ $personRole }}</p>
                @if(filled($contactEmail))
                    <p class="text-sm mt-3">Contact: <a href="mailto:{{ $contactEmail }}" class="text-brand-accent">{{ $contactEmail }}</a></p>
                @endif
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach($socialLinks as $social)
                        @if(filled($social['url']))
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="badge">{{ $social['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-5 mt-8">
        @foreach($milestones as $milestone)
            <article class="glass-card p-5">
                <p class="text-xs uppercase tracking-widest text-brand-muted">{{ $milestone['year'] }}</p>
                <p class="mt-3">{{ $milestone['summary'] }}</p>
            </article>
        @endforeach
    </div>

    @if($testimonials->isNotEmpty())
        <div class="mt-12">
            <div class="section-head">
                <p class="eyebrow">Client Voice</p>
                <h2 class="section-title">What clients say</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-5 mt-6">
                @foreach($testimonials as $testimonial)
                    <article class="glass-card p-5">
                        <p class="text-sm">"{{ $testimonial->quote }}"</p>
                        <div class="flex items-center gap-3 mt-4">
                            @if(filled($testimonial->avatar))
                                <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }} avatar" class="h-8 w-8 rounded-full object-cover border border-brand-border">
                            @endif
                            <p class="text-xs text-brand-muted">{{ $testimonial->name }} @if($testimonial->company) - {{ $testimonial->company }} @endif</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
