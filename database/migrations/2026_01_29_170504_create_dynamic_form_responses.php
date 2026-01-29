<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dynamic_form_responses', function (Blueprint $table) {
            $table->id();

            // Relación con dynamic_forms
            $table->foreignId('dynamic_form_id')
                ->constrained('dynamic_forms')
                ->cascadeOnDelete();

            // Usuario que responde el formulario
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Tipo de usuario (docente, dictaminador, secretaria, etc.)
            $table->string('user_type')->nullable();

            // Datos dinámicos del formulario (JSON)
            $table->json('data');

            // Evitar duplicados: un usuario solo puede tener una respuesta por formulario
            $table->unique(['dynamic_form_id', 'user_id', 'user_type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_form_responses');
    }
};
