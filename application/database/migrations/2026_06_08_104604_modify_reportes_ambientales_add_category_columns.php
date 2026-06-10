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
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->json('resultados_aire')->nullable()->after('resultados');
            $table->json('resultados_ruido')->nullable()->after('resultados_aire');
            $table->json('resultados_gases')->nullable()->after('resultados_ruido');
            $table->string('subtipo_ruido')->nullable()->after('comentarios');
            $table->text('observaciones_aire')->nullable()->after('subtipo_ruido');
            $table->text('observaciones_ruido')->nullable()->after('observaciones_aire');
            $table->text('observaciones_gases')->nullable()->after('observaciones_ruido');
        });
    }

    public function down(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn([
                'resultados_aire',
                'resultados_ruido',
                'resultados_gases',
                'subtipo_ruido',
                'observaciones_aire',
                'observaciones_ruido',
                'observaciones_gases',
            ]);
        });
    }
};
