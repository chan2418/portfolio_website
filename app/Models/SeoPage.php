<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $fillable = [
        'page_type',
        'page_key',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'robots_directive',
        'schema_markup',
    ];
}
