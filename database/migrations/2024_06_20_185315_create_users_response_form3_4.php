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
        Schema::create('users_response_form3_4', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
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
            $table->string('obs3_4_1')->nullable(); // Allow null values
            $table->string('obs3_4_2')->nullable(); // Allow null values
            $table->string('obs3_4_3')->nullable(); // Allow null values
            $table->string('obs3_4_4')->nullable(); // Allow null values
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY obs3_4_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY obs3_4_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY obs3_4_3 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY obs3_4_4 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");

        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY cantInternacional DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY cantNacional DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY cantidadRegional DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_4 MODIFY cantPreparacion DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");



    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_4');
    }
};
