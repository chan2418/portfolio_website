<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->foreignId('project_type_id')
                ->nullable()
                ->after('id')
                ->constrained('project_types')
                ->nullOnDelete();

            $table->index('project_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('project_type_id');
        });
    }
};
