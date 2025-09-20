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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->text('url');
            $table->integer('status_code');
            $table->decimal('response_time_ms', 10, 2);
            $table->json('request_headers')->nullable();
            $table->json('response_headers')->nullable();
            $table->json('request_body')->nullable();
            $table->json('response_body')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();

            $table->index(['project_id', 'logged_at']);
            $table->index(['status_code', 'logged_at']);
            $table->index(['user_id', 'logged_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
