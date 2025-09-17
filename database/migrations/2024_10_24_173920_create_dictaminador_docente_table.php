<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictaminadorDocenteTable extends Migration
{
public function up()
{
    Schema::create('dictaminador_docente', function (Blueprint $table) {
        $table->id();
        
        $table->unsignedBigInteger('user_id'); // Relaciona con docentes
        $table->bigInteger('dictaminador_id')->unsigned();
        $table->string('form_type')->nullable(); // Para identificar el tipo de formulario
        $table->string('docente_email')->nullable();
        $table->timestamps();

        

    });



    }

    public function down()
    {
        Schema::dropIfExists('dictaminador_docente');
    }
}

