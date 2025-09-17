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
        Schema::create('dictaminators_response_form3_9', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_9', 9, 2);
            $table->decimal('comision3_9', 9, 2);

            $table->string('obs3_9_1')->default('sin comentarios');
            $table->decimal('puntaje3_9_1', 9, 2);
            $table->decimal('tutorias1', 9, 2);
            $table->decimal('tutoriasComision1', 9, 2);

            for ($i = 2; $i <= 17; $i++) {
                $table->string("obs3_9_$i")->default('sin comentarios');
                $table->decimal("puntaje3_9_$i", 9, 2);
                $table->decimal("tutorias$i", 9, 2);
                $table->decimal("tutoriasComision$i", 9, 2);
            }


            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_9');
    }
};
