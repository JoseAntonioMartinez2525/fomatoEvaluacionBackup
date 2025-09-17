<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('puntajes_maximos', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique(); // Ej: 'puntajeMaximo'
            $table->integer('valor'); // Valor del puntaje máximo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('puntajes_maximos');
    }
};
