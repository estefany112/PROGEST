<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hacer la columna fecha_documento opcional (como fecha_aprox)
        DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NULL;");
    }

    public function down(): void
    {
        // Si quieres revertirlo a NOT NULL
        DB::statement("ALTER TABLE contrasenas_pago MODIFY fecha_documento DATE NOT NULL;");
    }
};
