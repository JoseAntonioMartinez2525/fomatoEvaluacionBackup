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
            // Añadimos las nuevas columnas JSON después de 'puntaje_maximo'
            $table->json('form_structure')->nullable()->after('puntaje_maximo');
            $table->json('form_data')->nullable()->after('form_structure');

            // Eliminamos la columna 'table_data' que será reemplazada
            if (Schema::hasColumn('dynamic_forms', 'table_data')) {
                $table->dropColumn('table_data');
            }
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->dropColumn(['form_structure', 'form_data']);
            $table->json('table_data')->nullable()->after('puntaje_maximo');
        });
    }
};

