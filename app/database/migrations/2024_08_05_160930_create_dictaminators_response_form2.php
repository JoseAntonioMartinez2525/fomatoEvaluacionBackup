<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictaminatorsResponseForm2 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dictaminators_response_form2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->string('email');
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->decimal('horasActv2', 8, 2);
            $table->decimal('puntajeEvaluar', 8, 2);
            $table->decimal('comision1', 8, 2);
            $table->text('obs1')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
        \DB::statement("ALTER TABLE dictaminators_response_form2 MODIFY obs1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form2');
    }
}
;
