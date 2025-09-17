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
        Schema::create('dictaminators_response_form3_2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_2', 8, 2);
            $table->decimal('comision3_2', 8, 2);
            $table->decimal('r1', 8, 2);
            $table->decimal('r2', 8, 2);
            $table->decimal('r3', 8, 2);
            $table->decimal('cant1', 8, 2);
            $table->decimal('cant2', 8, 2);
            $table->decimal('cant3', 8, 2);
            $table->decimal('prom90_100', 8, 2);
            $table->decimal('prom80_90', 8, 2);
            $table->decimal('prom70_80', 8, 2);
            $table->string('obs3_2_1')->default('sin comentarios'); // Default value
            $table->string('obs3_2_2')->default('sin comentarios'); // Default value
            $table->string('obs3_2_3')->default('sin comentarios'); // Default value
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_2');
    }
};
