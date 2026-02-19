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
        Schema::table('docentes', function (Blueprint $table) {
            if (!Schema::hasColumn('docentes', 'maestroId')) {
                // Se usa bigInteger (camelCase) y se agrega index() para búsquedas rápidas
                $table->string('maestroId',10)->nullable()->after('id');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            if (Schema::hasColumn('docentes', 'maestroId')) {
                $table->dropColumn('maestroId');
            }
        });
    }
};
