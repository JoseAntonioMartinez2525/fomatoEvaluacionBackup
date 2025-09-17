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
        Schema::create('evaluador_por_firmas', function (Blueprint $table) {
            //$table->bigInteger('dictaminador_id')->unsigned();
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->boolean('evaluator_order')->default(0);
            $table->string('evaluator_name')->nullable();
            $table->string('evaluator_name_2')->nullable();
            $table->string('evaluator_name_3')->nullable();
            
            $table->string('signature_path')->nullable();
            $table->string('signature_path_2')->nullable();
            $table->string('signature_path_3')->nullable();
            // Campo para indicar el orden de las firmas enviadas
            $table->boolean('signature_order')->default(0); //0 significa verdader 
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluador_por_firmas');
    }
};
