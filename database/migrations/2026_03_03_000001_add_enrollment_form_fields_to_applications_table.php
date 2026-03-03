<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'with_lrn')) {
                $table->boolean('with_lrn')->nullable();
            }
            if (!Schema::hasColumn('applications', 'lrn')) {
                $table->string('lrn', 20)->nullable();
            }
            if (!Schema::hasColumn('applications', 'returning_learner')) {
                $table->boolean('returning_learner')->nullable();
            }
            if (!Schema::hasColumn('applications', 'psa_birth_certificate_no')) {
                $table->string('psa_birth_certificate_no')->nullable();
            }
            if (!Schema::hasColumn('applications', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'middle_name')) {
                $table->string('middle_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'birthdate')) {
                $table->date('birthdate')->nullable();
            }
            if (!Schema::hasColumn('applications', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable();
            }
            if (!Schema::hasColumn('applications', 'mother_tongue')) {
                $table->string('mother_tongue')->nullable();
            }
            if (!Schema::hasColumn('applications', 'has_ip_affiliation')) {
                $table->boolean('has_ip_affiliation')->nullable();
            }
            if (!Schema::hasColumn('applications', 'ip_affiliation')) {
                $table->string('ip_affiliation')->nullable();
            }
            if (!Schema::hasColumn('applications', 'is_4ps_beneficiary')) {
                $table->boolean('is_4ps_beneficiary')->nullable();
            }
            if (!Schema::hasColumn('applications', 'four_ps_household_id')) {
                $table->string('four_ps_household_id')->nullable();
            }
            if (!Schema::hasColumn('applications', 'is_lwd')) {
                $table->boolean('is_lwd')->nullable();
            }
            if (!Schema::hasColumn('applications', 'disability_types')) {
                $table->json('disability_types')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_house_no')) {
                $table->string('current_house_no')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_street')) {
                $table->string('current_street')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_barangay')) {
                $table->string('current_barangay')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_municipality')) {
                $table->string('current_municipality')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_province')) {
                $table->string('current_province')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_country')) {
                $table->string('current_country')->nullable();
            }
            if (!Schema::hasColumn('applications', 'current_zip_code')) {
                $table->string('current_zip_code', 20)->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_house_no')) {
                $table->string('permanent_house_no')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_street')) {
                $table->string('permanent_street')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_barangay')) {
                $table->string('permanent_barangay')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_municipality')) {
                $table->string('permanent_municipality')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_province')) {
                $table->string('permanent_province')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_country')) {
                $table->string('permanent_country')->nullable();
            }
            if (!Schema::hasColumn('applications', 'permanent_zip_code')) {
                $table->string('permanent_zip_code', 20)->nullable();
            }
            if (!Schema::hasColumn('applications', 'father_last_name')) {
                $table->string('father_last_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'father_first_name')) {
                $table->string('father_first_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'father_middle_name')) {
                $table->string('father_middle_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'father_contact_number')) {
                $table->string('father_contact_number')->nullable();
            }
            if (!Schema::hasColumn('applications', 'mother_last_name')) {
                $table->string('mother_last_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'mother_first_name')) {
                $table->string('mother_first_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'mother_middle_name')) {
                $table->string('mother_middle_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'mother_contact_number')) {
                $table->string('mother_contact_number')->nullable();
            }
            if (!Schema::hasColumn('applications', 'guardian_last_name')) {
                $table->string('guardian_last_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'guardian_first_name')) {
                $table->string('guardian_first_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'guardian_middle_name')) {
                $table->string('guardian_middle_name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'guardian_contact_number')) {
                $table->string('guardian_contact_number')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            foreach ([
                'with_lrn',
                'lrn',
                'returning_learner',
                'psa_birth_certificate_no',
                'last_name',
                'first_name',
                'middle_name',
                'birthdate',
                'place_of_birth',
                'mother_tongue',
                'has_ip_affiliation',
                'ip_affiliation',
                'is_4ps_beneficiary',
                'four_ps_household_id',
                'is_lwd',
                'disability_types',
                'current_house_no',
                'current_street',
                'current_barangay',
                'current_municipality',
                'current_province',
                'current_country',
                'current_zip_code',
                'permanent_house_no',
                'permanent_street',
                'permanent_barangay',
                'permanent_municipality',
                'permanent_province',
                'permanent_country',
                'permanent_zip_code',
                'father_last_name',
                'father_first_name',
                'father_middle_name',
                'father_contact_number',
                'mother_last_name',
                'mother_first_name',
                'mother_middle_name',
                'mother_contact_number',
                'guardian_last_name',
                'guardian_first_name',
                'guardian_middle_name',
                'guardian_contact_number',
            ] as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
