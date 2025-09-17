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
        Schema::create('dictaminators_response_form3_3', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_3', 8, 2);
            $table->decimal('rc1', 8, 2);
            $table->decimal('rc2', 8, 2);
            $table->decimal('rc3', 8, 2);
            $table->decimal('rc4', 8, 2);
            $table->decimal('stotal1', 8, 2);
            $table->decimal('stotal2', 8, 2);
            $table->decimal('stotal3', 8, 2);
            $table->decimal('stotal4', 8, 2);
            $table->decimal('comision3_3', 8, 2);
            $table->decimal('comIncisoA', 8, 2);
            $table->decimal('comIncisoB', 8, 2);
            $table->decimal('comIncisoC', 8, 2);
            $table->decimal('comIncisoD', 8, 2);
            $table->string('obs3_3_1')->default('sin comentarios'); // Default value
            $table->string('obs3_3_2')->default('sin comentarios'); // Default value
            $table->string('obs3_3_3')->default('sin comentarios'); // Default value 
            $table->string('obs3_3_4')->default('sin comentarios'); // Default
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_3');
    }
};
