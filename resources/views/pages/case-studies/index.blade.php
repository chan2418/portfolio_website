@extends('layouts.site')

@section('content')
<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">Projects</p>
        <h1 class="section-title">Project stories with measurable outcomes</h1>
        <p class="text-brand-muted mt-3 max-w-2xl">Explore the stack, process, and impact behind each build.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @forelse($projects as $project)
            <article class="glass-card p-6 card-hover">
                <p class="text-xs uppercase tracking-widest text-brand-muted">{{ $project->industry ?: 'Digital Product' }}</p>
                <h2 class="font-display text-xl mt-2">{{ $project->title }}</h2>
                <p class="text-sm text-brand-muted mt-3">{{ $project->summary }}</p>
                <div class="mt-4 flex items-center justify-between text-xs text-brand-muted">
                    <span>{{ optional($project->published_at)->format('M Y') ?: 'In progress' }}</span>
                    @if($project->is_featured)
                        <span class="badge">Featured</span>
                    @endif
                </div>
                <a href="{{ route('case-studies.show', $project->slug) }}" class="text-sm mt-4 inline-flex text-brand-accent">View Project -></a>
            </article>
        @empty
            <p class="text-brand-muted">No published case studies yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $projects->links() }}</div>
</section>
@endsection
