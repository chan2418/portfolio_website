@extends('layouts.site')

@section('content')
<section class="container-main">
    <div class="section-head">
        <p class="eyebrow">Services</p>
        <h1 class="section-title">Ways I can support your project</h1>
        <p class="text-brand-muted mt-3 max-w-2xl">From planning to delivery, each service is focused on clear outcomes, quality execution, and long-term maintainability.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mt-8">
        @forelse($services as $service)
            <article class="glass-card p-6">
                <p class="text-xs uppercase tracking-widest text-brand-muted">{{ $service->icon ?: 'Service' }}</p>
                <h2 class="font-display text-2xl mt-3">{{ $service->title }}</h2>
                @if($service->excerpt)
                    <p class="text-brand-muted mt-2">{{ $service->excerpt }}</p>
                @endif
                <div class="mt-4 prose-lite">{!! nl2br(e($service->description)) !!}</div>
            </article>
        @empty
            <p class="text-brand-muted">No services published yet.</p>
        @endforelse
    </div>
</section>
@endsection
