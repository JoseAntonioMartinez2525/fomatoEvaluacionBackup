<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConsolidatedResponsesTable extends Migration
{
    public function up()
    {
        Schema::table('consolidated_responses', function (Blueprint $table) {
            // Ciclo para agregar comisiones dinámicas
            for ($i = 2; $i <= 19; $i++) {
                // Detectar y agregar subelementos dinámicamente
                $j = 1;
                while ($this->hasSubElement($i, $j)) {
                    $table->decimal("comision3_{$i}_{$j}", 8, 2)->nullable();
                    $j++;
                }
            }
        });
    }

    public function down()
    {
        Schema::table('consolidated_responses', function (Blueprint $table) {
            // Ciclo para eliminar comisiones dinámicas
            for ($i = 2; $i <= 19; $i++) {
                // Detectar y eliminar subelementos dinámicamente
                $j = 1;
                while ($this->hasSubElement($i, $j)) {
                    $table->dropColumn("comision3_{$i}_{$j}");
                    $j++;
                }
            }
        });
    }

    private function hasSubElement($i, $j)
    {
        // Limitar el número de subelementos para evitar bucles infinitos
        $maxSubElements = 5; // Ajusta este valor según tus necesidades

        // Simula un patrón dinámico para determinar si hay subelementos
        // Aquí podrías validar contra un conjunto de datos o una convención
        return $j <= $maxSubElements;
    }
}