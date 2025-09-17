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
        Schema::create('users_response_form3_16', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_16', 8, 2);
            $table->decimal('cantArbInt', 8, 2);
            $table->decimal('subtotalArbInt', 8, 2);
            $table->decimal('cantArbNac', 8, 2);
            $table->decimal('subtotalArbNac', 8, 2);
            $table->decimal('cantPubInt', 8, 2);
            $table->decimal('subtotalPubInt', 8, 2);
            $table->decimal('cantPubNac', 8, 2);
            $table->decimal('subtotalPubNac', 8, 2);
            $table->decimal('cantRevInt', 8, 2);
            $table->decimal('subtotalRevInt', 8, 2);
            $table->decimal('cantRevNac', 8, 2);
            $table->decimal('subtotalRevNac', 8, 2);
            $table->decimal('cantRevista', 8, 2);
            $table->decimal('subtotalRevista', 8, 2);
            $table->string('obsArbInt')->nullable();
            $table->string('obsArbNac')->nullable();
            $table->string('obsPubInt')->nullable();
            $table->string('obsPubNac')->nullable();
            $table->string('obsRevInt')->nullable();
            $table->string('obsRevNac')->nullable();
            $table->string('obsRevista')->nullable();

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsArbInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsArbNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsPubInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsPubNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsRevInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsRevNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");  
        \DB::statement("ALTER TABLE users_response_form3_16 MODIFY obsRevista VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_16');
    }
};