<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\BlogPost;
use App\Support\SeoManager;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(10);

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'blog', [
            'title' => 'Blog',
            'description' => 'Technical and product insights on Laravel engineering, UX strategy, and growth execution.',
        ]);

        return view('pages.blog.index', compact('posts', 'seo'));
    }

    public function show(string $slug, SeoManager $seoManager): View|Response
    {
        $post = BlogPost::published()->where('slug', $slug)->first();

        if (! $post) {
            abort(404);
        }

        $seo = $seoManager->resolve(SeoPageType::Blog->value, $post->slug, [
            'title' => $post->title,
            'description' => $post->excerpt ?: str($post->content)->stripTags()->limit(160)->toString(),
            'og_image' => $post->cover_image,
        ]);

        return view('pages.blog.show', compact('post', 'seo'));
    }
}
