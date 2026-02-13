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
        Schema::table('docentes', function (Blueprint $table) {
            if (!Schema::hasColumn('docentes', 'periodo')) {
                $table->string('periodo')->nullable()->after('area');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('docentes')) {
            Schema::table('docentes', function (Blueprint $table) {
                if (Schema::hasColumn('docentes', 'periodo')) {
                    $table->dropColumn('periodo');
                }
            });
        }
    }
};
