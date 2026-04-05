<x-filament-panels::page>
    @php
        $report = $this->getReport();
        $statusClasses = [
            'pass' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-300 border-emerald-500/30',
            'warn' => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/30',
            'fail' => 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border-rose-500/30',
        ];
    @endphp

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs uppercase tracking-wide text-gray-500">Readiness Score</p>
            <p class="mt-2 text-3xl font-semibold">{{ $report['score'] }}%</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs uppercase tracking-wide text-gray-500">Passed</p>
            <p class="mt-2 text-3xl font-semibold text-emerald-600 dark:text-emerald-300">{{ $report['summary']['pass'] }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs uppercase tracking-wide text-gray-500">Warnings</p>
            <p class="mt-2 text-3xl font-semibold text-amber-600 dark:text-amber-300">{{ $report['summary']['warn'] }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs uppercase tracking-wide text-gray-500">Failed</p>
            <p class="mt-2 text-3xl font-semibold text-rose-600 dark:text-rose-300">{{ $report['summary']['fail'] }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-0 dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-950/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Check</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['checks'] as $check)
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $check['label'] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$check['status']] }}">
                                {{ strtoupper($check['status']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $check['message'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('filament.admin.pages.brand-profile') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800">
            Update Brand Profile
        </a>
        <a href="{{ route('filament.admin.resources.projects.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800">
            Manage Projects
        </a>
        <a href="{{ route('filament.admin.resources.blog-posts.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800">
            Manage Blog Posts
        </a>
        <a href="{{ route('filament.admin.resources.seo-pages.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800">
            Manage SEO Pages
        </a>
    </div>
</x-filament-panels::page>
