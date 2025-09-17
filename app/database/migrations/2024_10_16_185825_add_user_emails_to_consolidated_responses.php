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
        Schema::table('consolidated_responses', function (Blueprint $table) {
            $table->json('user_email')->change()->nullable(); // Añadir la columna como JSON para manejar múltiples correos
        });
    }

    public function down(): void
    {
        Schema::table('consolidated_responses', function (Blueprint $table) {
            // $table->dropColumn('user_email');
            $table->string('user_email')->change()->nullable();
        });
    }

};
