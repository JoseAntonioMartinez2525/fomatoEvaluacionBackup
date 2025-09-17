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
        Schema::create('users_response_form3_9', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_9', 8, 2);
            $table->decimal('puntaje3_9_1', 8, 2);
            $table->decimal('puntaje3_9_2', 8, 2);
            $table->decimal('puntaje3_9_3', 8, 2);
            $table->decimal('puntaje3_9_4', 8, 2);
            $table->decimal('puntaje3_9_5', 8, 2);
            $table->decimal('puntaje3_9_6', 8, 2);
            $table->decimal('puntaje3_9_7', 8, 2);
            $table->decimal('puntaje3_9_8', 8, 2);
            $table->decimal('puntaje3_9_9', 8, 2);
            $table->decimal('puntaje3_9_10', 8, 2);
            $table->decimal('puntaje3_9_11', 8, 2);
            $table->decimal('puntaje3_9_12', 8, 2);
            $table->decimal('puntaje3_9_13', 8, 2);
            $table->decimal('puntaje3_9_14', 8, 2);
            $table->decimal('puntaje3_9_15', 8, 2);
            $table->decimal('puntaje3_9_16', 8, 2);
            $table->decimal('puntaje3_9_17', 8, 2);
            $table->decimal('tutorias1', 8, 2);
            $table->decimal('tutorias2', 8, 2);
            $table->decimal('tutorias3', 8, 2);
            $table->decimal('tutorias4', 8, 2);
            $table->decimal('tutorias5', 8, 2);
            $table->decimal('tutorias6', 8, 2);
            $table->decimal('tutorias7', 8, 2);
            $table->decimal('tutorias8', 8, 2);
            $table->decimal('tutorias9', 8, 2);
            $table->decimal('tutorias10', 8, 2);
            $table->decimal('tutorias11', 8, 2);
            $table->decimal('tutorias12', 8, 2);
            $table->decimal('tutorias13', 8, 2);
            $table->decimal('tutorias14', 8, 2);
            $table->decimal('tutorias15', 8, 2);
            $table->decimal('tutorias16', 8, 2);
            $table->decimal('tutorias17', 8, 2);                        
            $table->string('obs3_9_1')->nullable(); 
            $table->string('obs3_9_2')->nullable(); 
            $table->string('obs3_9_3')->nullable();
            $table->string('obs3_9_4')->nullable(); 
            $table->string('obs3_9_5')->nullable(); 
            $table->string('obs3_9_6')->nullable(); 
            $table->string('obs3_9_7')->nullable(); 
            $table->string('obs3_9_8')->nullable(); 
            $table->string('obs3_9_9')->nullable();
            $table->string('obs3_9_10')->nullable();
            $table->string('obs3_9_11')->nullable();
            $table->string('obs3_9_12')->nullable();
            $table->string('obs3_9_13')->nullable();
            $table->string('obs3_9_14')->nullable();
            $table->string('obs3_9_15')->nullable();
            $table->string('obs3_9_16')->nullable();
            $table->string('obs3_9_17')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_3 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_4 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_5 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_6 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_7 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_8 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_9 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_10 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_11 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_12 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_13 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_14 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_15 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_16 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY obs3_9_17 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");

        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_1 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_2 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_3 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_4 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_5 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_6 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_7 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_8 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_9 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_10 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_11 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_12 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_13 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_14 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_15 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_16 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_9 MODIFY puntaje3_9_17 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_9');
    }
};
