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
        Schema::create('dynamic_form_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id'); // Clave foránea
            $table->string('key'); // Clave de cada dato
            $table->text('value')->nullable(); // Valor del dato
            $table->decimal('puntaje_maximo', 8, 2);
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
        Schema::dropIfExists('dynamic_form_items');
    }
};
