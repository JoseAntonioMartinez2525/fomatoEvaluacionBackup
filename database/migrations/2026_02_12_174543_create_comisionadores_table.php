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
        Schema::create('comisionadores', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('nombre');
            $table->string('primerApellido');
            $table->string('segundoApellido')->nullable();
            $table->string('email')->unique();
            $table->string('departamento')->nullable();
            $table->string('area')->nullable();
            
            // Base64 puede ser muy largo, usamos longText
            $table->longText('firma_grafica')->nullable();
            
            // Campo JSON para las fechas
            $table->json('fecha_convocatoria')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisionadores');
    }
};
