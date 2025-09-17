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
        Schema::create('dictaminators_response_form3_4', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_4', 8, 2);
            $table->decimal('cantInternacional', 8, 2);
            $table->decimal('cantNacional', 8, 2);
            $table->decimal('cantidadRegional', 8, 2);
            $table->decimal('cantPreparacion', 8, 2);
            $table->decimal('cantInternacional2', 8, 2);
            $table->decimal('cantNacional2', 8, 2);
            $table->decimal('cantidadRegional2', 8, 2);
            $table->decimal('cantPreparacion2', 8, 2);
            $table->decimal('comision3_4', 8, 2);
            $table->decimal('comInternacional', 8, 2);
            $table->decimal('comNacional', 8, 2);
            $table->decimal('comRegional', 8, 2);
            $table->decimal('comPreparacion', 8, 2);
            $table->string('obs3_4_1')->default('sin comentarios'); // Default value
            $table->string('obs3_4_2')->default('sin comentarios'); // Default value
            $table->string('obs3_4_3')->default('sin comentarios'); // Default value 
            $table->string('obs3_4_4')->default('sin comentarios'); // Default
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_4');
    }
};
