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
        Schema::table('dynamic_form_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('evaluador_id')->nullable()->after('data');
            $table->string('evaluador_email')->nullable()->after('evaluador_id');

            // Opcional: Definir la llave foránea si deseas integridad referencial estricta
            $table->foreign('evaluador_id')->references('id')->on('users')->nullOnDelete();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_form_responses', function (Blueprint $table) {
            // Es necesario eliminar la llave foránea antes de borrar la columna
            $table->dropForeign(['evaluador_id']);
            $table->dropColumn(['evaluador_id', 'evaluador_email']);
        });
    }
};