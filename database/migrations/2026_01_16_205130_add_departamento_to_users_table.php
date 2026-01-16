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
        Schema::table('users', function (Blueprint $table) {
            $table->string('departamento')->nullable()->after('is_dictaminador');
        });

        // Actualizar masivamente los usuarios existentes usando la configuración
        $emails = config('dictaminadores.emails', []);
        $departamentos = config('dictaminadores.departamentos', []);

        foreach ($emails as $index => $email) {
            if (isset($departamentos[$index])) {
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('email', $email)
                    ->update(['departamento' => $departamentos[$index]]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('departamento');
        });
    }
};
