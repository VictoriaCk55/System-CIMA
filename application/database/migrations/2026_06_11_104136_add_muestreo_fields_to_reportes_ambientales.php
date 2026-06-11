<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->date('fecha_inicio_muestreo')->nullable()->after('fecha_medicion');
            $table->date('fecha_fin_muestreo')->nullable()->after('fecha_inicio_muestreo');
            $table->string('tipo_muestreo', 100)->nullable()->after('periodo_medicion');
            $table->text('condiciones_muestreo')->nullable()->after('equipo_usado');
            $table->text('condiciones_reporte')->nullable()->after('condiciones_muestreo');
        });
    }

    public function down(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_inicio_muestreo',
                'fecha_fin_muestreo',
                'tipo_muestreo',
                'condiciones_muestreo',
                'condiciones_reporte',
            ]);
        });
    }
};
