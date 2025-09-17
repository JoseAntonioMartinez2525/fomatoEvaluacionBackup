<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dictaminators_response_form3_10', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_10', 10, 2);
            $table->decimal('comision3_10', 10, 2);
            $table->decimal('grupalesCant', 10, 2);
            $table->decimal('evaluarGrupales', 10,2);
            $table->decimal('evaluarIndividual', 10,2);
            $table->decimal('individualCant', 10,2);
            $table->decimal('comisionGrupal', 10, 2);
            $table->decimal('comisionIndividual', 10, 2);
            $table->string('obsGrupal')->default('sin comentarios'); 
            $table->string('obsIndividual')->default('sin comentarios'); 
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_10');
    }
};
