<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\SeoPage;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Support\SiteSettings;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->upsert([
            ['key' => 'site_name', 'value' => 'Portfolio Core Studio', 'group' => 'branding', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Product-grade portfolio systems that convert traffic into clients.', 'group' => 'branding', 'label' => 'Site Tagline'],
            ['key' => 'brand_person_name', 'value' => 'Your Name', 'group' => 'branding', 'label' => 'Person Name'],
            ['key' => 'brand_role', 'value' => 'Laravel Developer & Product Engineer', 'group' => 'branding', 'label' => 'Primary Role'],
            ['key' => 'brand_short_bio', 'value' => 'I build high-performing portfolio and product websites that help personal brands turn visitors into clients.', 'group' => 'branding', 'label' => 'Short Bio'],
            ['key' => 'brand_long_bio', 'value' => 'I specialize in Laravel development, conversion-focused UX, and SEO-ready content systems. My goal is simple: help founders and creators present their work with clarity and authority.', 'group' => 'branding', 'label' => 'Long Bio'],
            ['key' => 'profile_photo_url', 'value' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=900&q=80', 'group' => 'branding', 'label' => 'Profile Photo URL'],
            ['key' => 'lead_notification_email', 'value' => env('ADMIN_EMAIL', 'admin@portfolio-core.test'), 'group' => 'notifications', 'label' => 'Lead Notification Email'],
            ['key' => 'contact_email', 'value' => env('CONTACT_EMAIL', env('ADMIN_EMAIL', 'admin@portfolio-core.test')), 'group' => 'contact', 'label' => 'Public Contact Email'],
            ['key' => 'social_linkedin_url', 'value' => 'https://linkedin.com/in/your-username', 'group' => 'social', 'label' => 'LinkedIn URL'],
            ['key' => 'social_github_url', 'value' => 'https://github.com/your-username', 'group' => 'social', 'label' => 'GitHub URL'],
            ['key' => 'social_x_url', 'value' => 'https://x.com/your-username', 'group' => 'social', 'label' => 'X URL'],
            ['key' => 'default_og_image', 'value' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=1400&q=80', 'group' => 'seo', 'label' => 'Default OG Image'],
            ['key' => 'robots_txt', 'value' => "User-agent: *\nAllow: /\n", 'group' => 'seo', 'label' => 'robots.txt'],
        ], ['key'], ['value', 'group', 'label', 'updated_at']);

        $services = [
            [
                'title' => 'Laravel Product Engineering',
                'slug' => 'laravel-product-engineering',
                'excerpt' => 'Custom Laravel architecture, modules, and launch-ready implementation.',
                'description' => 'Build robust product systems with modular Laravel architecture, test coverage, and production hardening.',
                'icon' => 'heroicon-o-code-bracket-square',
                'order_column' => 1,
            ],
            [
                'title' => 'Conversion UX & Interface Systems',
                'slug' => 'conversion-ux-interface-systems',
                'excerpt' => 'Intentional UX designed to improve trust, clarity, and conversion rate.',
                'description' => 'Craft premium interfaces aligned with business goals, user behavior, and conversion flows.',
                'icon' => 'heroicon-o-sparkles',
                'order_column' => 2,
            ],
            [
                'title' => 'SEO Content Operations',
                'slug' => 'seo-content-operations',
                'excerpt' => 'Structured publishing and SEO architecture to compound organic traffic.',
                'description' => 'Design and operate SEO-ready blog systems with technical metadata, schema, and content cadence.',
                'icon' => 'heroicon-o-chart-bar-square',
                'order_column' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                array_merge($service, [
                    'is_active' => true,
                    'published_at' => now()->subDays(10),
                ])
            );
        }

        $project = Project::query()->updateOrCreate(
            ['slug' => 'portfolio-core-platform'],
            [
                'title' => 'Portfolio Core Platform',
                'client_name' => 'Independent Studio',
                'industry' => 'Professional Services',
                'summary' => 'Built a complete portfolio engine with CMS, CRM, and SEO workflows.',
                'challenge' => '<p>The old portfolio was static and not generating qualified leads consistently.</p>',
                'solution' => '<p>Implemented a Laravel 12 core with Filament admin, lead pipeline, SEO controls, and premium frontend UX.</p>',
                'results' => '<ul><li>Improved lead capture quality through structured inquiry flows.</li><li>Reduced content update time with admin-managed modules.</li><li>Enabled long-term SEO growth with blog + metadata controls.</li></ul>',
                'tech_stack' => ['Laravel 12', 'Filament', 'MySQL', 'Tailwind CSS'],
                'metrics' => [
                    'Lead-to-call rate' => '+46%',
                    'Content publish speed' => '3x faster',
                    'Admin efficiency' => 'Single dashboard',
                ],
                'cover_image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1200&q=80',
                'project_url' => 'https://example.com',
                'is_featured' => true,
                'status' => PublishStatus::Published->value,
                'published_at' => now()->subDays(30),
            ]
        );

        BlogPost::query()->updateOrCreate(
            ['slug' => 'building-a-portfolio-that-converts'],
            [
                'title' => 'Building a Portfolio That Converts Clients, Not Just Views',
                'excerpt' => 'How to structure portfolio pages for authority, trust, and measurable conversion.',
                'content' => '<p>Most portfolios look good but fail to convert because they lack positioning and buyer clarity.</p><p>A strong system includes case studies, service framing, and clear conversion pathways.</p>',
                'tags' => ['portfolio', 'conversion', 'laravel'],
                'reading_time_minutes' => 6,
                'status' => PublishStatus::Published->value,
                'published_at' => now()->subDays(7),
            ]
        );

        Testimonial::query()->updateOrCreate(
            ['name' => 'Aarav K'],
            [
                'role' => 'Founder',
                'company' => 'Fluxpeak Labs',
                'quote' => 'The platform made our online presence feel like a serious product business, not a freelancer profile.',
                'rating' => 5,
                'order_column' => 1,
                'is_active' => true,
            ]
        );

        Faq::query()->updateOrCreate(
            ['question' => 'How long does a full-core portfolio build take?'],
            [
                'answer' => 'A production MVP usually takes 3-6 weeks depending on content readiness and integration scope.',
                'order_column' => 1,
                'is_active' => true,
            ]
        );

        $seoDefaults = [
            ['page_type' => 'static', 'page_key' => 'home', 'meta_title' => 'Portfolio Core Studio', 'meta_description' => 'Product-grade portfolio platform for serious growth.'],
            ['page_type' => 'static', 'page_key' => 'services', 'meta_title' => 'Services | Portfolio Core Studio', 'meta_description' => 'Engineering, UX, and SEO operations services.'],
            ['page_type' => 'static', 'page_key' => 'case-studies', 'meta_title' => 'Case Studies | Portfolio Core Studio', 'meta_description' => 'Outcome-driven case studies from real projects.'],
            ['page_type' => 'static', 'page_key' => 'blog', 'meta_title' => 'Blog | Portfolio Core Studio', 'meta_description' => 'Insights on Laravel, product systems, and growth.'],
            ['page_type' => 'static', 'page_key' => 'about', 'meta_title' => 'About | Portfolio Core Studio', 'meta_description' => 'How strategy and execution come together.'],
            ['page_type' => 'static', 'page_key' => 'contact', 'meta_title' => 'Contact | Portfolio Core Studio', 'meta_description' => 'Start your portfolio transformation project.'],
            ['page_type' => 'project', 'page_key' => $project->slug, 'meta_title' => $project->title.' | Case Study', 'meta_description' => $project->summary],
        ];

        foreach ($seoDefaults as $entry) {
            SeoPage::query()->updateOrCreate(
                ['page_type' => $entry['page_type'], 'page_key' => $entry['page_key']],
                $entry
            );
        }

        SiteSettings::clear();
    }
}
