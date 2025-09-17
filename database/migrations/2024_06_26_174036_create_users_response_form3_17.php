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
        Schema::create('users_response_form3_17', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_17', 8, 2);
            $table->decimal('cantDifusionExt', 8, 2);
            $table->decimal('subtotalDifusionExt', 8, 2);
            $table->decimal('cantDifusionInt', 8, 2);
            $table->decimal('subtotalDifusionInt', 8, 2);
            $table->decimal('cantRepDifusionExt', 8, 2);
            $table->decimal('subtotalRepDifusionExt', 8, 2);
            $table->decimal('cantRepDifusionInt', 8, 2);
            $table->decimal('subtotalRepDifusionInt', 8, 2);
            $table->string('obsDifusionExt')->nullable();
            $table->string('obsDifusionInt')->nullable();
            $table->string('obsRepDifusionExt')->nullable();
            $table->string('obsRepDifusionInt')->nullable();


            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_17 MODIFY obsDifusionExt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_17 MODIFY obsDifusionInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_17 MODIFY obsRepDifusionExt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_17 MODIFY obsRepDifusionInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_17');
    }
};