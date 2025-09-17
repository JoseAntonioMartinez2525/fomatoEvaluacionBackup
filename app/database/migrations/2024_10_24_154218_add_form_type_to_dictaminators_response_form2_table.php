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
        Schema::table('dictaminators_response_form2', function (Blueprint $table) {
            $table->string('form_type')->default('form2'); // Ajusta el tipo de datos según tus necesidades
  //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dictaminators_response_form2', function (Blueprint $table) {
            //
        });
    }
};
