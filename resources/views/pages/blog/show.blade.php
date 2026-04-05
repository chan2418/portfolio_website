@extends('layouts.site')

@section('content')
<section class="container-main">
    <a href="{{ route('blog.index') }}" class="text-sm text-brand-muted"><- Back to blog</a>

    <article class="glass-card p-7 mt-4">
        <p class="text-xs uppercase tracking-widest text-brand-muted">
            {{ optional($post->published_at)->format('d M Y') ?: 'Draft' }} - {{ $post->reading_time_minutes }} min read
        </p>
        <h1 class="section-title mt-2">{{ $post->title }}</h1>

        @if($post->excerpt)
            <p class="text-brand-muted mt-3 max-w-3xl">{{ $post->excerpt }}</p>
        @endif

        @if(!empty($post->tags))
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($post->tags as $tag)
                    <span class="badge">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        <div class="prose-lite mt-8">{!! $post->content !!}</div>
    </article>
</section>
@endsection
