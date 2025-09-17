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
        Schema::create('users_response_form3_3', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
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
            $table->string('obs3_3_1')->nullable(); // Allow null values
            $table->string('obs3_3_2')->nullable(); // Allow null values
            $table->string('obs3_3_3')->nullable(); // Allow null values
            $table->string('obs3_3_4')->nullable(); // Allow null values
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY obs3_3_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY obs3_3_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY obs3_3_3 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY obs3_3_4 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");

        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY rc1 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY rc2 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY rc3 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_3 MODIFY rc4 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_3');
    }
};
