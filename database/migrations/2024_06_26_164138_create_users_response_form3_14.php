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
        Schema::create('users_response_form3_14', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_14', 8, 2);
            $table->decimal('cantCongresoInt', 8, 2);
            $table->decimal('subtotalCongresoInt', 8, 2);
            $table->decimal('cantCongresoNac', 8, 2);
            $table->decimal('subtotalCongresoNac', 8, 2);
            $table->decimal('cantCongresoLoc', 8, 2);
            $table->decimal('subtotalCongresoLoc', 8, 2);
            $table->string('obsCongresoInt')->nullable();
            $table->string('obsCongresoNac')->nullable();
            $table->string('obsCongresoLoc')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_14 MODIFY obsCongresoInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_14 MODIFY obsCongresoNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_14 MODIFY obsCongresoLoc VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_14');
    }
};