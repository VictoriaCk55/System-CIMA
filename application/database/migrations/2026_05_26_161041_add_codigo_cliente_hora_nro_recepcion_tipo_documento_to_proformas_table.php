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
        Schema::table('proformas', function (Blueprint $table) {
            if (! Schema::hasColumn('proformas', 'tipo_documento')) {
                $table->json('tipo_documento')->nullable()->after('tipo');
            }
            if (! Schema::hasColumn('proformas', 'hora_recepcion')) {
                $table->time('hora_recepcion')->nullable()->after('fecha_recepcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->dropColumn(['tipo_documento', 'hora_recepcion']);
        });
    }
};
