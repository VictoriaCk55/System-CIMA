<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable()->after('nombre');
            $table->string('descripcion')->nullable()->after('metodo');
            $table->string('tipo_medicion')->nullable()->after('categoria');
            $table->string('unidad_default')->nullable()->after('unidad');
        });
    }

    public function down(): void
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn(['nombre_completo', 'descripcion', 'tipo_medicion', 'unidad_default']);
        });
    }
};
