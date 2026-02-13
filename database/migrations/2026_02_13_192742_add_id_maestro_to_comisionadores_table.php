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
        Schema::table('comisionadores', function (Blueprint $table) {
            if (!Schema::hasColumn('comisionadores', 'id_maestro')) {
                // Se usa bigInteger (camelCase) y se agrega index() para búsquedas rápidas
                $table->bigInteger('id_maestro')->nullable()->index()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('comisionadores')) {
            Schema::table('comisionadores', function (Blueprint $table) {
                if (Schema::hasColumn('comisionadores', 'id_maestro')) {
                    $table->dropColumn('id_maestro');
                }
            });
        }
    }
};
