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
        Schema::create('dynamic_form_combined', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id'); // Foreign key to dynamic_forms
            $table->unsignedBigInteger('dynamic_form_column_id'); // Foreign key to dynamic_form_columns
            $table->unsignedBigInteger('dynamic_form_value_id'); // Foreign key to dynamic_form_values
            $table->string('form_name'); // Form name from dynamic_forms
            $table->decimal('puntaje_maximo', 8, 2); // Max score from dynamic_forms
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('dynamic_form_id')
                ->references('id')
                ->on('dynamic_forms')
                ->onDelete('cascade');

            $table->foreign('dynamic_form_column_id')
                ->references('id')
                ->on('dynamic_form_columns')
                ->onDelete('cascade');

            $table->foreign('dynamic_form_value_id')
                ->references('id')
                ->on('dynamic_form_values')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_form_combined');
    }
};
