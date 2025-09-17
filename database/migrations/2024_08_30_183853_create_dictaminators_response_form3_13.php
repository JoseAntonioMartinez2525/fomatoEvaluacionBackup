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
        Schema::create('dictaminators_response_form3_13', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_13', 8, 2);

            $table->decimal('cantInicioFinanExt', 8, 2);
            $table->decimal('subtotalInicioFinanExt', 8, 2);
            $table->decimal('comisionInicioFinancimientoExt', 8, 2);
            $table->string('obsInicioFinancimientoExt')->default('sin comentarios');

            $table->decimal('cantInicioInvInterno', 8, 2);
            $table->decimal('subtotalInicioInvInterno', 8, 2);
            $table->decimal('comisionInicioInvInterno', 8, 2);
            $table->string('obsInicioInvInterno')->default('sin comentarios');

            $table->decimal('cantReporteFinanciamExt', 8, 2);
            $table->decimal('subtotalReporteFinanciamExt', 8, 2);
            $table->decimal('comisionReporteFinanciamExt', 8, 2);
            $table->string('obsReporteFinanciamExt')->default('sin comentarios');

            $table->decimal('cantReporteInvInt', 8, 2);
            $table->decimal('subtotalReporteInvInt', 8, 2);
            $table->decimal('comisionReporteInvInt', 8, 2);
            $table->string('obsReporteInvInt')->default('sin comentarios');

            $table->decimal('comision3_13', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_13');
    }
};
