<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $industries = DB::table('projects')
            ->whereNull('project_type_id')
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->distinct()
            ->pluck('industry');

        foreach ($industries as $industry) {
            $baseSlug = Str::slug((string) $industry);
            $slugRoot = $baseSlug !== '' ? $baseSlug : 'project-type';
            $slug = $slugRoot;
            $suffix = 1;

            while (DB::table('project_types')->where('slug', $slug)->exists()) {
                $matchingType = DB::table('project_types')
                    ->where('slug', $slug)
                    ->first();

                if ($matchingType && $matchingType->name === $industry) {
                    break;
                }

                $slug = $slugRoot.'-'.$suffix;
                $suffix++;
            }

            $existingType = DB::table('project_types')
                ->where('name', $industry)
                ->orWhere('slug', $slug)
                ->first();

            $projectTypeId = $existingType?->id;

            if (! $projectTypeId) {
                $projectTypeId = DB::table('project_types')->insertGetId([
                    'name' => $industry,
                    'slug' => $slug,
                    'description' => null,
                    'cover_image' => null,
                    'order_column' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('projects')
                ->whereNull('project_type_id')
                ->where('industry', $industry)
                ->update(['project_type_id' => $projectTypeId]);
        }
    }

    public function down(): void
    {
        // Intentionally left blank to avoid destructive rollback of mapped production data.
    }
};
