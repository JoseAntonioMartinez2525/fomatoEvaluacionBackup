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
        Schema::table('generated_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('generated_reports', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('generated_reports', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('generated_reports', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('generated_reports', 'error_message')) {
                $table->text('error_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_reports', function (Blueprint $table) {
            $table->dropColumn(['status', 'file_name', 'file_path', 'error_message']);
        });
    }
};
