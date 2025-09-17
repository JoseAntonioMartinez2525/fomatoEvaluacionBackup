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
        Schema::create('users_response_form3_13', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_13', 8, 2);
            $table->decimal('cantInicioFinanExt', 8, 2);
            $table->decimal('subtotalInicioFinanExt', 8, 2);
            $table->decimal('cantInicioInvInterno', 8, 2);
            $table->decimal('subtotalInicioInvInterno', 8, 2);
            $table->decimal('cantReporteFinanciamExt', 8, 2);
            $table->decimal('subtotalReporteFinanciamExt', 8, 2);
            $table->decimal('cantReporteInvInt', 8, 2);
            $table->decimal('subtotalReporteInvInt', 8, 2);
            $table->string('obsInicioFinancimientoExt')->nullable();
            $table->string('obsInicioInvInterno')->nullable();
            $table->string('obsReporteFinanciamExt')->nullable();
            $table->string('obsReporteInvInt')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_13 MODIFY obsInicioFinancimientoExt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_13 MODIFY obsInicioInvInterno VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_13 MODIFY obsReporteFinanciamExt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_13 MODIFY obsReporteInvInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_13');
    }
};