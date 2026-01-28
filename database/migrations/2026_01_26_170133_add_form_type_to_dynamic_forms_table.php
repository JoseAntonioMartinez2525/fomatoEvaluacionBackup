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
        Schema::table('dynamic_forms', function (Blueprint $table) {
            // Añadimos la nueva columna después de 'form_name'
            $table->string('form_type')->nullable()->after('user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->dropColumn('form_type');
        });
    }
};
