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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 500);
            $table->text('description');
            $table->string('status', 20)->default('pendiente');
            $table->string('priority', 20)->default('media');
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->uuid('assigned_to_id')->nullable();
            $table->foreign('assigned_to_id')->references('id')->on('employees')->onDelete('set null');
            $table->uuid('requested_by_id');
            $table->foreign('requested_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->boolean('is_archived')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
