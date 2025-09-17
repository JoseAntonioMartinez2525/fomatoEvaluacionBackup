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
        Schema::create('users_response_form3_6', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_6', 8, 2);
            $table->decimal('puntaje3_6', 8, 2);
            $table->decimal('puntajeHoras3_6', 8, 2); 
            $table->string('obs3_6_1')->nullable(); // Allow null values
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();

            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_6 MODIFY obs3_6_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_6 MODIFY puntaje3_6 DECIMAL(8,2) DEFAULT 0.0 NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_6');
    }
};
