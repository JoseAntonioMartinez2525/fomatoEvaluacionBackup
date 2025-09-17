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
        Schema::create('dictaminador_docente_form2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dictaminador_id');
            $table->string('dictaminador_email');
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->string('docente_email');
            $table->decimal('horasActv2', 8, 2);
            $table->decimal('puntajeEvaluar', 8, 2);
            $table->decimal('comision1', 8, 2);
            $table->text('obs1')->nullable();
            // Asegúrate de que las claves foráneas sean correctdictaminador_docente_form2as
            $table->foreign('dictaminador_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
        \DB::statement("ALTER TABLE dictaminador_docente_form2 MODIFY obs1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminador_docente_form2');
    }
};
