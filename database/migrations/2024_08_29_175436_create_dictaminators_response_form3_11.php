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
        Schema::create('dictaminators_response_form3_11', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_11', 8, 2);
            $table->decimal('comision3_11', 8, 2);
            $table->decimal('cantAsesoria', 8, 2);
            $table->decimal('cantServicio', 8, 2);
            $table->decimal('cantPracticas', 8, 2);
            $table->decimal('subtotalAsesoria', 8, 2);
            $table->decimal('subtotalServicio', 8, 2);
            $table->decimal('subtotalPracticas', 8, 2);
            $table->decimal('comisionAsesoria', 8, 2);
            $table->decimal('comisionServicio', 8, 2);
            $table->decimal('comisionPracticas', 8, 2);
            $table->string('obsAsesoria')->default('sin comentarios'); // Default value
            $table->string('obsServicio')->default('sin comentarios'); // Default value
            $table->string('obsPracticas')->default('sin comentarios'); // Default value
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_11');
    }
};
