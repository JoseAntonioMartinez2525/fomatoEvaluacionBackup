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
        Schema::create('users_final_resume', function (Blueprint $table) {
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('dictaminador_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->integer('comision_actividad_1_total');
            $table->integer('comision_actividad_2_total');
            $table->integer('comision_actividad_3_total');
            $table->integer('total_puntaje');
            $table->string('minima_calidad');
            $table->string('minima_total');
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_final_resume');
    }
};
