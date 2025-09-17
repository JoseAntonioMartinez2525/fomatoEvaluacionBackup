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
        Schema::table('dictaminators_response_form3_10', function (Blueprint $table) {
            $table->string('form_type')->default('form3_10');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dictaminators_response_form3_10', function (Blueprint $table) {
            //
        });
    }
};
