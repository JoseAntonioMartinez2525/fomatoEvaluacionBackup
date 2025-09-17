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
        Schema::create('dictaminators_response_form3_5', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_5', 8, 2);
            $table->decimal('cantDA', 8, 2);
            $table->decimal('cantCAAC', 8, 2);
            $table->decimal('cantDA2', 8, 2);
            $table->decimal('cantCAAC2', 8, 2);
            $table->decimal('comision3_5', 8, 2);
            $table->decimal('comDA', 8, 2);
            $table->decimal('comNCAA', 8, 2);
            $table->string('obs3_5_1')->default('sin comentarios'); // Default value
            $table->string('obs3_5_2')->default('sin comentarios'); // Default value

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_5');
    }
};
