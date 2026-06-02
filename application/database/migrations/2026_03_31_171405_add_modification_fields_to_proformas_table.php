<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            // Campo para la unidad (UIA o UAQ) - después de tipo_muestra
            $table->string('unidad', 10)->nullable()->after('tipo_muestra');

            // Campo para indicar si los parámetros fueron modificados
            $table->boolean('parametros_modificados')->default(false)->after('estado');

            // Campo para almacenar la justificación
            $table->text('justificacion_modificacion')->nullable()->after('parametros_modificados');

            // Campo para registrar quién modificó
            $table->foreignId('modificado_por')->nullable()->after('justificacion_modificacion')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->dropForeign(['modificado_por']);
            $table->dropColumn([
                'unidad',
                'parametros_modificados',
                'justificacion_modificacion',
                'modificado_por',
            ]);
        });
    }
};
