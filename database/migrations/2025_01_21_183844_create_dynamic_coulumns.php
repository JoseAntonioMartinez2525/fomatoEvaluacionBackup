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
        Schema::create('dynamic_form_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id'); // Clave foránea
            $table->string('column_name'); // Nombre de la columna dinámica
            $table->timestamps();

            // Relación con la tabla 'dynamic_forms'
            $table->foreign('dynamic_form_id')
                ->references('id')
                ->on('dynamic_forms')
                ->onDelete('cascade'); // Elimina los registros hijos al eliminar el padre
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('dynamic_form_columns');
    }
};
