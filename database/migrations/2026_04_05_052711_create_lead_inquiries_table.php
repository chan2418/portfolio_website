<?php

use App\Enums\LeadStage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_inquiries', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('budget')->nullable();
            $table->string('service_interest')->nullable();
            $table->string('project_timeline')->nullable();
            $table->text('message');
            $table->string('source')->default('website');
            $table->string('stage', 20)->default(LeadStage::New->value);
            $table->text('status_note')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_inquiries');
    }
};
