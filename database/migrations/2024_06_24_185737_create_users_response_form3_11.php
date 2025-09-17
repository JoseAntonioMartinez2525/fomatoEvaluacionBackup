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
        Schema::create('users_response_form3_11', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_11', 8, 2);
            $table->decimal('cantAsesoria', 8, 2);
            $table->decimal('cantServicio', 8, 2);
            $table->decimal('cantPracticas', 8, 2);
            $table->decimal('subtotalAsesoria', 8, 2);
            $table->decimal('subtotalServicio', 8, 2);
            $table->decimal('subtotalPracticas', 8, 2);
            $table->string('obsAsesoria')->nullable();
            $table->string('obsServicio')->nullable();
            $table->string('obsPracticas')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_11 MODIFY obsAsesoria VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_11 MODIFY obsServicio VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_11 MODIFY obsPracticas VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_11');
    }
};
