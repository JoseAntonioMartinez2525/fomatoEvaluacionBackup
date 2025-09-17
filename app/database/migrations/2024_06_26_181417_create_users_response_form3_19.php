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
        Schema::create('users_response_form3_19', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_19', 8, 2);
            $table->decimal('cantCGUtitular', 8, 2);
            $table->decimal('subtotalCGUtitular', 8, 2);
            $table->decimal('cantCGUespecial', 8, 2);
            $table->decimal('subtotalCGUespecial', 8, 2);
            $table->decimal('cantCGUpermanente', 8, 2);
            $table->decimal('subtotalCGUpermanente', 8, 2);
            $table->decimal('cantCAACtitular', 8, 2);
            $table->decimal('subtotalCAACtitular', 8, 2);
            $table->decimal('cantCAACintegCom', 8, 2);
            $table->decimal('subtotalCAACintegCom', 8, 2);
            $table->decimal('cantComDepart', 8, 2);
            $table->decimal('subtotalComDepart', 8, 2);
            $table->decimal('cantComPEDPD', 8, 2);
            $table->decimal('subtotalComPEDPD', 8, 2);
            $table->decimal('cantComPartPos', 8, 2);
            $table->decimal('subtotalComPartPos', 8, 2);
            $table->decimal('cantRespPos', 8, 2);
            $table->decimal('subtotalRespPos', 8, 2);
            $table->decimal('cantRespCarrera', 8, 2);
            $table->decimal('subtotalRespCarrera', 8, 2);
            $table->decimal('cantRespProd', 8, 2);
            $table->decimal('subtotalRespProd', 8, 2);
            $table->decimal('cantRespLab', 8, 2);
            $table->decimal('subtotalRespLab', 8, 2);
            $table->decimal('cantExamProf', 8, 2);
            $table->decimal('subtotalExamProf', 8, 2);
            $table->decimal('cantExamAcademicos', 8, 2);
            $table->decimal('subtotalExamAcademicos', 8, 2);
            $table->decimal('cantPRODEPformResp', 8, 2);
            $table->decimal('subtotalPRODEPformResp', 8, 2);
            $table->decimal('cantPRODEPformInteg', 8, 2);
            $table->decimal('subtotalPRODEPformInteg', 8, 2);
            $table->decimal('cantPRODEPenconsResp', 8, 2);
            $table->decimal('subtotalPRODEPenconsResp', 8, 2);
            $table->decimal('cantPRODEPenconsInteg', 8, 2);
            $table->decimal('subtotalPRODEPenconsInteg', 8, 2);
            $table->decimal('cantPRODEPconsResp', 8, 2);
            $table->decimal('subtotalPRODEPconsResp', 8, 2);
            $table->decimal('cantPRODEPconsInteg', 8, 2);
            $table->decimal('subtotalPRODEPconsInteg', 8, 2);
            $table->string('obsCGUtitular')->nullable();
            $table->string('obsCGUespecial')->nullable();
            $table->string('obsCGUpermanente')->nullable();
            $table->string('obsCAACtitular')->nullable();
            $table->string('obsCAACintegCom')->nullable();
            $table->string('obsComDepart')->nullable();
            $table->string('obsComPEDPD')->nullable();
            $table->string('obsComPartPos')->nullable();
            $table->string('obsRespPos')->nullable();
            $table->string('obsRespCarrera')->nullable();
            $table->string('obsRespProd')->nullable();
            $table->string('obsRespLab')->nullable();
            $table->string('obsExamProf')->nullable();
            $table->string('obsExamAcademicos')->nullable();
            $table->string('obsPRODEPformResp')->nullable();
            $table->string('obsPRODEPformInteg')->nullable();
            $table->string('obsPRODEPenconsResp')->nullable();
            $table->string('obsPRODEPenconsInteg')->nullable();
            $table->string('obsPRODEPconsResp')->nullable();
            $table->string('obsPRODEPconsInteg')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsCGUtitular VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsCGUespecial VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsCGUpermanente VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsCAACtitular VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsCAACintegCom VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsComDepart VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsComPEDPD VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsComPartPos VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsRespPos VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsRespCarrera VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsRespProd VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsRespLab VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsExamProf VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsExamAcademicos VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPformResp VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPformInteg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPenconsResp VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPenconsInteg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPconsResp VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_19 MODIFY obsPRODEPconsInteg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_19');
    }
};
