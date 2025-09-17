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
        Schema::create('dictaminators_response_form3_12', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_12', 12, 2);
            
            $table->decimal('cantCientifico', 8, 2);
            $table->decimal('subtotalCientificos', 8, 2);
            $table->decimal('comisionCientificos', 8, 2);
            $table->string('obsCientificos')->default('sin comentarios');

            $table->decimal('cantDivulgacion', 8, 2);
            $table->decimal('subtotalDivulgacion', 8, 2);
            $table->decimal('comisionDivulgacion', 8, 2);
            $table->string('obsDivulgacion')->default('sin comentarios');

            $table->decimal('cantTraduccion', 8, 2);
            $table->decimal('subtotalTraduccion', 8, 2);
            $table->decimal('comisionTraduccion', 8, 2);
            $table->string('obsTraduccion')->default('sin comentarios');

            $table->decimal('cantArbitrajeInt', 8, 2);
            $table->decimal('subtotalArbitrajeInt', 8, 2);
            $table->decimal('comisionArbitrajeInt', 8, 2);
            $table->string('obsArbitrajeInt')->default('sin comentarios');

            $table->decimal('cantArbitrajeNac', 8, 2);
            $table->decimal('subtotalArbitrajeNac', 8, 2);
            $table->decimal('comisionArbitrajeNac', 8, 2);
            $table->string('obsArbitrajeNac')->default('sin comentarios');

            $table->decimal('cantSinInt', 8, 2);
            $table->decimal('subtotalSinInt', 8, 2);
            $table->decimal('comisionSinInt', 8, 2);
            $table->string('obsSinInt')->default('sin comentarios');

            $table->decimal('cantSinNac', 8, 2);
            $table->decimal('subtotalSinNac', 8, 2);
            $table->decimal('comisionSinNac', 8, 2);
            $table->string('obsSinNac')->default('sin comentarios');

            $table->decimal('cantAutor', 8, 2);
            $table->decimal('subtotalAutor', 8, 2);
            $table->decimal('comisionAutor', 8, 2);
            $table->string('obsAutor')->default('sin comentarios');

            $table->decimal('cantEditor', 8, 2);
            $table->decimal('subtotalEditor', 8, 2);
            $table->decimal('comisionEditor', 8, 2);
            $table->string('obsEditor')->default('sin comentarios');

            $table->decimal('cantWeb', 8, 2);
            $table->decimal('subtotalWeb', 8, 2);
            $table->decimal('comisionWeb', 8, 2);
            $table->string('obsWeb')->default('sin comentarios');

            $table->decimal('comision3_12', 12, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_12');
    }
};
