@extends('layouts.site')

@section('content')
<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">Blog</p>
        <h1 class="section-title">Notes on projects, Laravel, and growth</h1>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mt-8">
        @forelse($posts as $post)
            <article class="glass-card p-6 card-hover">
                <p class="text-xs uppercase tracking-widest text-brand-muted">
                    {{ optional($post->published_at)->format('d M Y') ?: 'Draft' }} - {{ $post->reading_time_minutes }} min
                </p>
                <h2 class="font-display text-2xl mt-2">{{ $post->title }}</h2>
                <p class="text-brand-muted mt-3">{{ $post->excerpt ?: str(strip_tags($post->content))->limit(150) }}</p>
                <a href="{{ route('blog.show', $post->slug) }}" class="text-sm mt-4 inline-flex text-brand-accent">Read More -></a>
            </article>
        @empty
            <p class="text-brand-muted">No blog posts published yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
</section>
@endsection
