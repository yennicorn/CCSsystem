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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Student Information
            $table->string('student_first_name');
            $table->string('student_last_name');
            $table->string('grade_level');
            $table->string('school_year');

            // Parent Information
            $table->string('parent_name');
            $table->string('parent_contact');
            $table->string('parent_email');

            // Workflow Status (Two-Level Approval)
            $table->enum('status', [
                'pending',
                'reviewed',
                'approved',
                'rejected',
                'waitlisted'
            ])->default('pending');

            // Dual-Control Tracking
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Admin remarks
            $table->text('admin_notes')->nullable();
            $table->text('super_admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
