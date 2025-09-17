<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersResponseForm2 extends Migration
{
    public function up(): void
    {
        Schema::create('users_response_form2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->integer('horasActv2');
            $table->decimal('puntajeEvaluar', 8, 2);
            $table->string('obs1')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
        
        \DB::statement("ALTER TABLE users_response_form2 MODIFY obs1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form2 MODIFY horasActv2 DECIMAL(8, 2) DEFAULT 0.0 NOT NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('users_response_form2');
    }
}


