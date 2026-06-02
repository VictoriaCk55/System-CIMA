<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id();

            // Información básica
            $table->string('codigo')->unique()->comment('INF-001, INF-002...');
            $table->foreignId('proforma_id')->unique()->constrained('proformas')->onDelete('cascade');

            // Fechas
            $table->date('fecha_emision');
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_analisis')->nullable();
            $table->date('fecha_revision')->nullable();

            // Contenido técnico
            $table->text('resultado')->nullable()->comment('Resultados del análisis');
            $table->text('conclusiones')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->text('observaciones')->nullable();

            // Archivos adjuntos
            $table->string('archivo_adjunto')->nullable()->comment('PDF del informe escaneado');
            $table->string('archivo_resultados')->nullable()->comment('Archivo con datos crudos');

            // Estado y flujo de trabajo
            $table->enum('estado', ['BORRADOR', 'EN_PROCESO', 'REVISADO', 'APROBADO', 'ENTREGADO'])->default('BORRADOR');
            $table->enum('prioridad', ['BAJA', 'MEDIA', 'ALTA', 'URGENTE'])->default('MEDIA');

            // Responsables
            $table->foreignId('creado_por')->constrained('users');
            $table->foreignId('revisado_por')->nullable()->constrained('users');
            $table->foreignId('aprobado_por')->nullable()->constrained('users');
            $table->foreignId('entregado_por')->nullable()->constrained('users');

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas rápidas
            $table->index('codigo');
            $table->index('estado');
            $table->index('fecha_emision');
            $table->index('prioridad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
