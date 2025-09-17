<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsolidatedResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('consolidated_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_email');
            $table->enum('user_type', ['docente', 'dictaminador']);

            // Campos específicos para comisiones de dictaminadores
            $table->decimal('comision1', 8, 2)->nullable();
            $table->decimal('actv2Comision', 8, 2)->nullable();
            $table->decimal('actv3Comision', 8, 2)->nullable();
            for ($i = 2; $i <= 19; $i++) {
                $table->decimal("comision3_$i", 8, 2)->nullable();
            }

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('consolidated_responses');
    }
}
