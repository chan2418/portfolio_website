<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show'])->name('case-studies.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact/submit', [ContactController::class, 'submit'])
    ->middleware('throttle:6,1')
    ->name('contact.submit');

Route::get('/sitemap.xml', function () {
    if (! file_exists(public_path('sitemap.xml'))) {
        Artisan::call('app:generate-sitemap');
    }

    abort_unless(file_exists(public_path('sitemap.xml')), 404);

    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');

Route::get('/robots.txt', function () {
    $robots = SiteSettings::get('robots_txt')
        ?: "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n";

    return response($robots, 200, ['Content-Type' => 'text/plain']);
});
