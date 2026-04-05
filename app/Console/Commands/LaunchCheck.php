<?php

namespace App\Console\Commands;

use App\Support\LaunchReadinessChecker;
use Illuminate\Console\Command;

class LaunchCheck extends Command
{
    protected $signature = 'app:launch-check {--strict : Return non-zero when one or more checks fail}';

    protected $description = 'Run launch readiness checks for content, branding, SEO, and production configuration';

    public function handle(LaunchReadinessChecker $checker): int
    {
        $report = $checker->build();
        $summary = $report['summary'];

        $this->newLine();
        $this->info("Launch readiness score: {$report['score']}%");
        $this->line("Passed: {$summary['pass']}  Warnings: {$summary['warn']}  Failed: {$summary['fail']}");
        $this->newLine();

        $rows = collect($report['checks'])
            ->map(function (array $check): array {
                return [
                    $check['label'],
                    strtoupper($check['status']),
                    $check['message'],
                ];
            })
            ->all();

        $this->table(['Check', 'Status', 'Details'], $rows);

        if ((bool) $this->option('strict') && $summary['fail'] > 0) {
            $this->error('Strict mode enabled: resolve failed checks before launch.');

            return self::FAILURE;
        }

        if ($summary['fail'] > 0) {
            $this->warn('There are failed checks. Resolve them before production launch.');
        } elseif ($summary['warn'] > 0) {
            $this->warn('No failed checks, but warnings remain. Review before launch.');
        } else {
            $this->info('All launch checks passed.');
        }

        return self::SUCCESS;
    }
}
