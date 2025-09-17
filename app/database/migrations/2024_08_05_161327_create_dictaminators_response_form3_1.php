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
        Schema::create('dictaminators_response_form3_1', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dictaminador_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->decimal('elaboracion', 8, 2);
            $table->decimal('elaboracionSubTotal1', 8, 2);
            $table->decimal('elaboracion2', 8, 2);
            $table->decimal('elaboracionSubTotal2', 8, 2);
            $table->decimal('elaboracion3', 8, 2);
            $table->decimal('elaboracionSubTotal3', 8, 2);
            $table->decimal('elaboracion4', 8, 2);
            $table->decimal('elaboracionSubTotal4', 8, 2);
            $table->decimal('elaboracion5', 8, 2);
            $table->decimal('elaboracionSubTotal5', 8, 2);
            $table->decimal('comisionIncisoA', 8, 2);
            $table->decimal('comisionIncisoB', 8, 2);
            $table->decimal('comisionIncisoC', 8, 2);
            $table->decimal('comisionIncisoD', 8, 2);
            $table->decimal('comisionIncisoE', 8, 2);
            $table->decimal('score3_1', 8, 2);
            $table->decimal('actv3Comision', 8, 2);
            $table->string('obs3_1_1')->default('sin comentarios'); // Default value
            $table->string('obs3_1_2')->default('sin comentarios'); // Default value
            $table->string('obs3_1_3')->default('sin comentarios'); // Default value
            $table->string('obs3_1_4')->default('sin comentarios'); // Default value
            $table->string('obs3_1_5')->default('sin comentarios'); // Default value
            $table->enum('user_type', ['docente', 'dictaminador', ''])->nullable();
            $table->timestamps();
        });

        // Set default values for existing rows using raw SQL statements
       /* \DB::statement("ALTER TABLE dictaminators_response_form3_1 MODIFY obs3_1_1 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE dictaminators_response_form3_1 MODIFY obs3_1_2 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE dictaminators_response_form3_1 MODIFY obs3_1_3 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE dictaminators_response_form3_1 MODIFY obs3_1_4 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
        \DB::statement("ALTER TABLE dictaminators_response_form3_1 MODIFY obs3_1_5 VARCHAR(255) DEFAULT 'sin comentarios' NOT NULL");
    */
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictaminators_response_form3_1');
    }
};
