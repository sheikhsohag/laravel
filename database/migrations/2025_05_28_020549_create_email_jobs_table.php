<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
        {
            Schema::create('email_jobs', function (Blueprint $table) {
        $table->id();
        $table->string('batch_id')->nullable();
        $table->string('subject');
        $table->text('content');
        $table->integer('total_recipients');
        $table->integer('sent_count')->default(0);
        $table->integer('failed_count')->default(0);
        $table->string('status')->default('pending'); // pending, processing, completed, failed
        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_jobs');
    }
};
