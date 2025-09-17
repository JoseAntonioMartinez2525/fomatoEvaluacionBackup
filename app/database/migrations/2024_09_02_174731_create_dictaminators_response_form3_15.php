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
        Schema::create('dictaminators_response_form3_15', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_15', 8, 2);

            $table->decimal('cantPatentes', 8, 2);
            $table->decimal('subtotalPatentes', 8, 2);
            $table->decimal('comisionPatententes', 8, 2);
            $table->string('obsPatentes')->default('sin comentarios');

            $table->decimal('cantPrototipos', 8, 2);
            $table->decimal('subtotalPrototipos', 8, 2);
            $table->decimal('comisionPrototipos', 8, 2);
            $table->string('obsPrototipos')->default('sin comentarios');


            $table->decimal('comision3_15', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_15');
    }
};
