<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictaminatorsResponseForm22 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dictaminators_response_form2_2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id'); 
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->decimal('hours', 8, 2);
            $table->decimal('horasPosgrado', 8, 2);
            $table->decimal('horasSemestre', 8, 2);
            $table->decimal('dse', 8, 2);
            $table->decimal('dse2', 8, 2);
            $table->decimal('comisionPosgrado', 8, 2);
            $table->decimal('comisionLic', 8, 2);
            $table->decimal('actv2Comision', 8, 2);
            $table->string('obs2')->nullable();
            $table->string('obs2_2')->nullable();
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE dictaminators_response_form2_2 MODIFY obs2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE dictaminators_response_form2_2 MODIFY obs2_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form2_2');
    }
}
;
