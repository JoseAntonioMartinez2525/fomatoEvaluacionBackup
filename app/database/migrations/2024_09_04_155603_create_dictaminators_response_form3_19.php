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
        Schema::create('dictaminators_response_form3_19', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_19', 8, 2);

            $table->decimal('cantCGUtitular', 8, 2);
            $table->decimal('subtotalCGUtitular', 8, 2);
            $table->decimal('comCGUtitular', 8, 2);
            $table->string('obsCGUtitular')->default('sin comentarios');

            $table->decimal('cantCGUespecial', 8, 2);
            $table->decimal('subtotalCGUespecial', 8, 2);
            $table->decimal('comCGUespecial', 8, 2);
            $table->string('obsCGUespecial')->default('sin comentarios');

            $table->decimal('cantCGUpermanente', 8, 2);
            $table->decimal('subtotalCGUpermanente', 8, 2);
            $table->decimal('comCGUpermanente', 8, 2);
            $table->string('obsCGUpermanente')->default('sin comentarios');

            $table->decimal('cantCAACtitular', 8, 2);
            $table->decimal('subtotalCAACtitular', 8, 2);
            $table->decimal('comCAACtitular', 8, 2);
            $table->string('obsCAACtitular')->default('sin comentarios');

            $table->decimal('cantCAACintegCom', 8, 2);
            $table->decimal('subtotalCAACintegCom', 8, 2);
            $table->decimal('comCAACintegCom', 8, 2);
            $table->string('obsCAACintegCom')->default('sin comentarios');

            $table->decimal('cantComDepart', 8, 2);
            $table->decimal('subtotalComDepart', 8, 2);
            $table->decimal('comComDepart', 8, 2);
            $table->string('obsComDepart')->default('sin comentarios');

            $table->decimal('cantComPEDPD', 8, 2);
            $table->decimal('subtotalComPEDPD', 8, 2);
            $table->decimal('comComPEDPD', 8, 2);
            $table->string('obsComPEDPD')->default('sin comentarios');

            $table->decimal('cantComPartPos', 8, 2);
            $table->decimal('subtotalComPartPos', 8, 2);
            $table->decimal('comComPartPos', 8, 2);
            $table->string('obsComPartPos')->default('sin comentarios');

            $table->decimal('cantRespPos', 8, 2);
            $table->decimal('subtotalRespPos', 8, 2);
            $table->decimal('comRespPos', 8, 2);
            $table->string('obsRespPos')->default('sin comentarios');

            $table->decimal('cantRespCarrera', 8, 2);
            $table->decimal('subtotalRespCarrera', 8, 2);
            $table->decimal('comRespCarrera', 8, 2);
            $table->string('obsRespCarrera')->default('sin comentarios');

            $table->decimal('cantRespProd', 8, 2);
            $table->decimal('subtotalRespProd', 8, 2);
            $table->decimal('comRespProd', 8, 2);
            $table->string('obsRespProd')->default('sin comentarios');

            $table->decimal('cantRespLab', 8, 2);
            $table->decimal('subtotalRespLab', 8, 2);
            $table->decimal('comRespLab', 8, 2);
            $table->string('obsRespLab')->default('sin comentarios');

            $table->decimal('cantExamProf', 8, 2);
            $table->decimal('subtotalExamProf', 8, 2);
            $table->decimal('comExamProf', 8, 2);
            $table->string('obsExamProf')->default('sin comentarios');

            $table->decimal('cantExamAcademicos', 8, 2);
            $table->decimal('subtotalExamAcademicos', 8, 2);
            $table->decimal('comExamAcademicos', 8, 2);
            $table->string('obsExamAcademicos')->default('sin comentarios');

            $table->decimal('cantPRODEPformResp', 8, 2);
            $table->decimal('subtotalPRODEPformResp', 8, 2);
            $table->decimal('comPRODEPformResp', 8, 2);
            $table->string('obsPRODEPformResp')->default('sin comentarios');

            $table->decimal('cantPRODEPformInteg', 8, 2);
            $table->decimal('subtotalPRODEPformInteg', 8, 2);
            $table->decimal('comPRODEPformInteg', 8, 2);
            $table->string('obsPRODEPformInteg')->default('sin comentarios');

            $table->decimal('cantPRODEPenconsResp', 8, 2);
            $table->decimal('subtotalPRODEPenconsResp', 8, 2);
            $table->decimal('comPRODEPenconsResp', 8, 2);
            $table->string('obsPRODEPenconsResp')->default('sin comentarios');

            $table->decimal('cantPRODEPenconsInteg', 8, 2);
            $table->decimal('subtotalPRODEPenconsInteg', 8, 2);
            $table->decimal('comPRODEPenconsInteg', 8, 2);
            $table->string('obsPRODEPenconsInteg')->default('sin comentarios');

            $table->decimal('cantPRODEPconsResp', 8, 2);
            $table->decimal('subtotalPRODEPconsResp', 8, 2);
            $table->decimal('comPRODEPconsResp', 8, 2);
            $table->string('obsPRODEPconsResp')->default('sin comentarios');

            $table->decimal('cantPRODEPconsInteg', 8, 2);
            $table->decimal('subtotalPRODEPconsInteg', 8, 2);
            $table->decimal('comPRODEPconsInteg', 8, 2);
            $table->string('obsPRODEPconsInteg')->default('sin comentarios');

            $table->decimal('comision3_19', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_19');
    }
};
