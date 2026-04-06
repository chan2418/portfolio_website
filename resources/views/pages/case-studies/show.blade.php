@extends('layouts.site')

@section('content')
<section class="container-main">
    <a href="{{ route('case-studies.index') }}" class="text-sm text-brand-muted"><- Back to projects</a>

    <div class="glass-card p-7 mt-4">
        <p class="eyebrow">{{ $project->industry ?: 'Case Study' }}</p>
        <h1 class="section-title mt-2">{{ $project->title }}</h1>
        <p class="text-brand-muted mt-3 max-w-3xl">{{ $project->summary }}</p>

        @if($project->cover_image_url)
            <img
                src="{{ $project->cover_image_url }}"
                alt="{{ $project->title }} cover image"
                class="w-full h-64 md:h-80 object-cover rounded-2xl border border-brand-border mt-7"
                loading="lazy"
            >
        @endif

        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <article>
                <h2 class="font-display text-xl">Challenge</h2>
                <div class="prose-lite mt-2">{!! $project->challenge ?: '<p>Challenge details will be added soon.</p>' !!}</div>
            </article>
            <article>
                <h2 class="font-display text-xl">Solution</h2>
                <div class="prose-lite mt-2">{!! $project->solution ?: '<p>Solution details will be added soon.</p>' !!}</div>
            </article>
        </div>

        <article class="mt-8">
            <h2 class="font-display text-xl">Results</h2>
            <div class="prose-lite mt-2">{!! $project->results ?: '<p>Result details will be added soon.</p>' !!}</div>
        </article>

        @if(!empty($project->metrics))
            <div class="grid md:grid-cols-3 gap-4 mt-8">
                @foreach($project->metrics as $metric => $value)
                    <div class="metric-box">
                        <p class="metric-value">{{ $value }}</p>
                        <p class="metric-label">{{ $metric }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($project->tech_stack))
            <div class="mt-8">
                <h3 class="font-display text-lg">Technology Stack</h3>
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach($project->tech_stack as $tech)
                        <span class="badge">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" rel="noopener" class="btn-primary inline-flex mt-8">Visit Project</a>
        @endif
    </div>
</section>
@endsection
