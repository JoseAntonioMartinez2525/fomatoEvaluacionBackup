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
        Schema::create('dictaminators_response_form3_14', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_14', 8, 2);

            $table->decimal('cantCongresoInt', 8, 2);
            $table->decimal('subtotalCongresoInt', 8, 2);
            $table->decimal('comisionCongresoInt', 8, 2);
            $table->string('obsCongresoInt')->default('sin comentarios');

            $table->decimal('cantCongresoNac', 8, 2);
            $table->decimal('subtotalCongresoNac', 8, 2);
            $table->decimal('comisionCongresoNac', 8, 2);
            $table->string('obsCongresoNac')->default('sin comentarios');

            $table->decimal('cantCongresoLoc', 8, 2);
            $table->decimal('subtotalCongresoLoc', 8, 2);
            $table->decimal('comisionCongresoLoc', 8, 2);
            $table->string('obsCongresoLoc')->default('sin comentarios');

            $table->decimal('comision3_14', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_14');
    }
};
