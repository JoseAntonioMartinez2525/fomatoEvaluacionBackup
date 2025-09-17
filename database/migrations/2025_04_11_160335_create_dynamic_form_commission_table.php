<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicFormCommissionTable extends Migration
{
    public function up()
    {
        Schema::create('dynamic_form_commission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id'); // Relación con dynamic_forms
            $table->unsignedBigInteger('dynamic_form_column_id')->nullable(); // Relación con dynamic_form_columns
            $table->unsignedBigInteger('dynamic_form_value_id')->nullable(); // Relación con dynamic_form_values
            $table->unsignedBigInteger('user_id')->nullable(); // Relación con usuarios
            $table->string('email_docente')->nullable(); // Correo electrónico del usuario
            $table->string('row_identifier')->nullable(); // Identificador único para cada fila
            $table->decimal('puntaje_input_values', 10, 2)->nullable(); // Agregar las columna
            $table->decimal('puntaje_comision', 10, 2)->nullable(); // Puntaje de la comisión
            $table->text('observaciones')->nullable(); // Observaciones
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('dynamic_form_id')->references('id')->on('dynamic_forms')->onDelete('cascade');
            $table->foreign('dynamic_form_column_id')->references('id')->on('dynamic_form_columns')->onDelete('cascade');
            $table->foreign('dynamic_form_value_id')->references('id')->on('dynamic_form_values')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('email_docente')->references('email')->on('users')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('dynamic_form_commission');
    }
}