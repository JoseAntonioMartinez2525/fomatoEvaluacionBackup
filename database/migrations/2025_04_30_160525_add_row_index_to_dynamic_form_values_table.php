<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dynamic_form_values', function (Blueprint $table) {

            $table->unsignedInteger('row_index')->nullable()->after('dynamic_form_column_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_form_values', function (Blueprint $table) {
            // Drop the column if the migration is rolled back
            $table->dropColumn('row_index');
        });
    }
};