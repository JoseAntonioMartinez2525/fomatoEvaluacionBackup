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
        Schema::create('dictaminators_response_form3_18', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_18', 8, 2);

            $table->decimal('cantComOrgInt', 8, 2);
            $table->decimal('subtotalComOrgInt', 8, 2);
            $table->decimal('comisionComOrgInt', 8, 2);
            $table->string('obsComOrgInt')->default('sin comentarios');

            $table->decimal('cantComOrgNac', 8, 2);
            $table->decimal('subtotalComOrgNac', 8, 2);
            $table->decimal('comisionComOrgNac', 8, 2);
            $table->string('obsComOrgNac')->default('sin comentarios');

            $table->decimal('cantComOrgReg', 8, 2);
            $table->decimal('subtotalComOrgReg', 8, 2);
            $table->decimal('comisionComOrgReg', 8, 2);
            $table->string('obsComOrgReg')->default('sin comentarios');

            $table->decimal('cantComApoyoInt', 8, 2);
            $table->decimal('subtotalComApoyoInt', 8, 2);
            $table->decimal('comisionComApoyoInt', 8, 2);
            $table->string('obsComApoyoInt')->default('sin comentarios');

            $table->decimal('cantComApoyoNac', 8, 2);
            $table->decimal('subtotalComApoyoNac', 8, 2);
            $table->decimal('comisionComApoyoNac', 8, 2);
            $table->string('obsComApoyoNac')->default('sin comentarios');

            $table->decimal('cantComApoyoReg', 8, 2);
            $table->decimal('subtotalComApoyoReg', 8, 2);
            $table->decimal('comisionComApoyoReg', 8, 2);
            $table->string('obsComApoyoReg')->default('sin comentarios');

            $table->decimal('cantCicloComOrgInt', 8, 2);
            $table->decimal('subtotalCicloComOrgInt', 8, 2);
            $table->decimal('comisionCicloComOrgInt', 8, 2);
            $table->string('obsCicloComOrgInt')->default('sin comentarios');

            $table->decimal('cantCicloComOrgNac', 8, 2);
            $table->decimal('subtotalCicloComOrgNac', 8, 2);
            $table->decimal('comisionCicloComOrgNac', 8, 2);
            $table->string('obsCicloComOrgNac')->default('sin comentarios');

            $table->decimal('cantCicloComOrgReg', 8, 2);
            $table->decimal('subtotalCicloComOrgReg', 8, 2);
            $table->decimal('comisionCicloComOrgReg', 8, 2);
            $table->string('obsCicloComOrgReg')->default('sin comentarios');

            $table->decimal('cantCicloComApoyoInt', 8, 2);
            $table->decimal('subtotalCicloComApoyoInt', 8, 2);
            $table->decimal('comisionCicloComApoyoInt', 8, 2);
            $table->string('obsCicloComApoyoInt')->default('sin comentarios');

            $table->decimal('cantCicloComApoyoNac', 8, 2);
            $table->decimal('subtotalCicloComApoyoNac', 8, 2);
            $table->decimal('comisionCicloComApoyoNac', 8, 2);
            $table->string('obsCicloComApoyoNac')->default('sin comentarios');

            $table->decimal('cantCicloComApoyoReg', 8, 2);
            $table->decimal('subtotalCicloComApoyoReg', 8, 2);
            $table->decimal('comisionCicloComApoyoReg', 8, 2);
            $table->string('obsCicloComApoyoReg')->default('sin comentarios');


            $table->decimal('comision3_18', 8, 2);

            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_18');
    }
};
