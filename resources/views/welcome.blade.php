@extends('layouts.site')

@section('content')
<section class="container-main">
    <div class="glass-card p-8 text-center">
        <h1 class="section-title">Portfolio Core Platform</h1>
        <p class="text-brand-muted mt-3">Use the primary navigation to explore services, case studies, and blog content.</p>
        <a href="{{ route('home') }}" class="btn-primary inline-flex mt-5">Go to Home</a>
    </div>
</section>
@endsection
