<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Project;
use App\Support\SiteSettings;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate sitemap.xml for static pages, case studies, and blog posts';

    public function handle(): int
    {
        $baseUrl = rtrim((string) config('app.url'), '/');

        if (blank($baseUrl)) {
            $this->error('APP_URL is not configured.');

            return self::FAILURE;
        }

        $sitemap = Sitemap::create()
            ->add(Url::create("{$baseUrl}/")->setPriority(1.0))
            ->add(Url::create("{$baseUrl}/services")->setPriority(0.9))
            ->add(Url::create("{$baseUrl}/case-studies")->setPriority(0.9))
            ->add(Url::create("{$baseUrl}/blog")->setPriority(0.9))
            ->add(Url::create("{$baseUrl}/about")->setPriority(0.7))
            ->add(Url::create("{$baseUrl}/contact")->setPriority(0.8));

        Project::published()->get()->each(function (Project $project) use ($sitemap, $baseUrl): void {
            $sitemap->add(
                Url::create("{$baseUrl}/case-studies/{$project->slug}")
                    ->setPriority($project->is_featured ? 0.85 : 0.75)
                    ->setLastModificationDate($project->updated_at)
            );
        });

        BlogPost::published()->get()->each(function (BlogPost $post) use ($sitemap, $baseUrl): void {
            $sitemap->add(
                Url::create("{$baseUrl}/blog/{$post->slug}")
                    ->setPriority(0.7)
                    ->setLastModificationDate($post->updated_at)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $robotsTxt = SiteSettings::get('robots_txt')
            ?: "User-agent: *\nAllow: /\nSitemap: {$baseUrl}/sitemap.xml\n";

        file_put_contents(public_path('robots.txt'), $robotsTxt);

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return self::SUCCESS;
    }
}
