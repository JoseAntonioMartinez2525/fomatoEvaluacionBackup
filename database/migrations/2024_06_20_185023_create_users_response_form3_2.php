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
        Schema::create('users_response_form3_2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_2', 8, 2);
            $table->decimal('r1', 8, 2);
            $table->decimal('r2', 8, 2);
            $table->decimal('r3', 8, 2);
            $table->decimal('cant1', 8, 2);
            $table->decimal('cant2', 8, 2);
            $table->decimal('cant3', 8, 2);
            $table->string('obs3_2_1')->nullable(); // Allow null values
            $table->string('obs3_2_2')->nullable(); // Allow null values
            $table->string('obs3_2_3')->nullable(); // Allow null values
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY obs3_2_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY obs3_2_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY obs3_2_3 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");

        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY r1 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY r2 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_2 MODIFY r3 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_2');
    }
};
