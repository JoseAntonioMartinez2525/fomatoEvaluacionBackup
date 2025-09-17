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
        Schema::create('dictaminators_response_form3_16', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_16', 8, 2);
            
            $table->decimal('cantArbInt', 8, 2);
            $table->decimal('subtotalArbInt', 8, 2);
            $table->decimal('comisionArbInt', 8, 2);
            $table->string('obsArbInt')->default('sin comentarios');

            $table->decimal('cantArbNac', 8, 2);
            $table->decimal('subtotalArbNac', 8, 2);
            $table->decimal('comisionArbNac', 8, 2);
            $table->string('obsArbNac')->default('sin comentarios');

            $table->decimal('cantPubInt', 8, 2);
            $table->decimal('subtotalPubInt', 8, 2);
            $table->decimal('comisionPubInt', 8, 2);
            $table->string('obsPubInt')->default('sin comentarios');

            $table->decimal('cantPubNac', 8, 2);
            $table->decimal('subtotalPubNac', 8, 2);
            $table->decimal('comisionPubNac', 8, 2);
            $table->string('obsPubNac')->default('sin comentarios');

            $table->decimal('cantRevInt', 8, 2);
            $table->decimal('subtotalRevInt', 8, 2);
            $table->decimal('comisionRevInt', 8, 2);
            $table->string('obsRevInt')->default('sin comentarios');

            $table->decimal('cantRevNac', 8, 2);
            $table->decimal('subtotalRevNac', 8, 2);
            $table->decimal('comisionRevNac', 8, 2);
            $table->string('obsRevNac')->default('sin comentarios');

            $table->decimal('cantRevista', 8, 2);
            $table->decimal('subtotalRevista', 8, 2);
            $table->decimal('comisionRevista', 8, 2);
            $table->string('obsRevista')->default('sin comentarios');

            $table->decimal('comision3_16', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_16');
    }
};

