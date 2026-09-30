<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('job_offer_id')->constrained('job_offers');
            $table->enum('status', ['new', 'reviewing', 'interview', 'rejected', 'accepted'])->default('new');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'job_offer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
