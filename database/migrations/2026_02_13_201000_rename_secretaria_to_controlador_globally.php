<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up()
    {
        $tables = DB::select("
            SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME = 'user_type'
            AND DATA_TYPE = 'enum'
        ");

        foreach ($tables as $t) {

            $table = $t->TABLE_NAME;

            // 1️⃣ Expandir ENUM (respetando COLLATE y DEFAULT)
            DB::statement("
                ALTER TABLE `$table`
                CHANGE `user_type` `user_type`
                ENUM('docente','dictaminador','secretaria','controlador')
                COLLATE utf8mb4_unicode_ci
                DEFAULT NULL
            ");

            // 2️⃣ Actualizar datos
            DB::statement("
                UPDATE `$table`
                SET `user_type` = 'controlador'
                WHERE `user_type` = 'secretaria'
            ");

            // 3️⃣ Eliminar valor viejo
            DB::statement("
                ALTER TABLE `$table`
                CHANGE `user_type` `user_type`
                ENUM('docente','dictaminador','controlador')
                COLLATE utf8mb4_unicode_ci
                DEFAULT NULL
            ");
        }
    }

    public function down()
    {
        //
    }
};
