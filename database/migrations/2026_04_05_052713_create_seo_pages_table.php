<?php

use App\Enums\SeoPageType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('page_type', 20)->default(SeoPageType::Static->value);
            $table->string('page_key');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots_directive')->nullable();
            $table->longText('schema_markup')->nullable();
            $table->timestamps();

            $table->unique(['page_type', 'page_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
