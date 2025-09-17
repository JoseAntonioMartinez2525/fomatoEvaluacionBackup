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
        Schema::create('dictaminators_response_form3_17', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_17', 8, 2);

            $table->decimal('cantDifusionExt', 8, 2);
            $table->decimal('subtotalDifusionExt', 8, 2);
            $table->decimal('comisionDifusionExt', 8, 2);
            $table->string('obsDifusionExt')->default('sin comentarios');

            $table->decimal('cantDifusionInt', 8, 2);
            $table->decimal('subtotalDifusionInt', 8, 2);
            $table->decimal('comisionDifusionInt', 8, 2);
            $table->string('obsDifusionInt')->default('sin comentarios');

            $table->decimal('cantRepDifusionExt', 8, 2);
            $table->decimal('subtotalRepDifusionExt', 8, 2);
            $table->decimal('comisionRepDifusionExt', 8, 2);
            $table->string('obsRepDifusionExt')->default('sin comentarios');

            $table->decimal('cantRepDifusionInt', 8, 2);
            $table->decimal('subtotalRepDifusionInt', 8, 2);
            $table->decimal('comisionRepDifusionInt', 8, 2);
            $table->string('obsRepDifusionInt')->default('sin comentarios');

            $table->decimal('comision3_17', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_17');
    }
};
