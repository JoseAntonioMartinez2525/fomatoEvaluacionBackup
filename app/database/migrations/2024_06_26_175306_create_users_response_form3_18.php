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
        Schema::create('users_response_form3_18', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('score3_18', 8, 2);
            $table->decimal('cantComOrgInt', 8, 2);
            $table->decimal('subtotalComOrgInt', 8, 2);
            $table->decimal('cantComOrgNac', 8, 2);
            $table->decimal('subtotalComOrgNac', 8, 2);
            $table->decimal('cantComOrgReg', 8, 2);
            $table->decimal('subtotalComOrgReg', 8, 2);
            $table->decimal('cantComApoyoInt', 8, 2);
            $table->decimal('subtotalComApoyoInt', 8, 2);
            $table->decimal('cantComApoyoNac', 8, 2);
            $table->decimal('subtotalComApoyoNac', 8, 2);
            $table->decimal('cantComApoyoReg', 8, 2);
            $table->decimal('subtotalComApoyoReg', 8, 2);
            $table->decimal('cantCicloComOrgInt', 8, 2);
            $table->decimal('subtotalCicloComOrgInt', 8, 2);
            $table->decimal('cantCicloComOrgNac', 8, 2);
            $table->decimal('subtotalCicloComOrgNac', 8, 2);
            $table->decimal('cantCicloComOrgReg', 8, 2);
            $table->decimal('subtotalCicloComOrgReg', 8, 2);
            $table->decimal('cantCicloComApoyoInt', 8, 2);
            $table->decimal('subtotalCicloComApoyoInt', 8, 2);
            $table->decimal('cantCicloComApoyoNac', 8, 2);
            $table->decimal('subtotalCicloComApoyoNac', 8, 2);
            $table->decimal('cantCicloComApoyoReg', 8, 2);
            $table->decimal('subtotalCicloComApoyoReg', 8, 2);
            $table->string('obsComOrgInt')->nullable();
            $table->string('obsComOrgNac')->nullable();
            $table->string('obsComOrgReg')->nullable();
            $table->string('obsComApoyoInt')->nullable();
            $table->string('obsComApoyoNac')->nullable();
            $table->string('obsComApoyoReg')->nullable();
            $table->string('obsCicloComOrgInt')->nullable();
            $table->string('obsCicloComOrgNac')->nullable();
            $table->string('obsCicloComOrgReg')->nullable();
            $table->string('obsCicloComApoyoInt')->nullable();
            $table->string('obsCicloComApoyoNac')->nullable();
            $table->string('obsCicloComApoyoReg')->nullable();
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });


        // Set default values for existing rows using raw SQL statements
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComOrgInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComOrgNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComOrgReg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComApoyoInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComApoyoNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsComApoyoReg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComOrgInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComOrgNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComOrgReg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComApoyoInt VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComApoyoNac VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE users_response_form3_18 MODIFY obsCicloComApoyoReg VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_response_form3_18');
    }
};
