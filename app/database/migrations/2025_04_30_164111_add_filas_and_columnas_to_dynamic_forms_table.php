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
        Schema::table('dynamic_forms', function (Blueprint $table) {

            $table->unsignedInteger('filas')->nullable()->after('acreditacion'); // Add after acreditacion column
            $table->unsignedInteger('columnas')->nullable()->after('filas'); // Add after filas column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            // Drop the columns if the migration is rolled back
            $table->dropColumn(['filas', 'columnas']);
        });
    }
};
