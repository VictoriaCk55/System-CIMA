<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_ambientales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->constrained()->onDelete('cascade');
            $table->string('codigo_reporte')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_medicion')->nullable();
            $table->string('tipo_medicion')->nullable();
            $table->string('periodo_medicion')->nullable();
            $table->string('medicion_efectuada_por')->nullable();
            $table->string('equipo_usado')->nullable();
            $table->text('comentarios')->nullable();
            $table->string('responsable_uia')->nullable();
            $table->string('cargo_responsable')->nullable();
            $table->string('directora_cima')->nullable();
            $table->string('cargo_directora')->nullable();
            $table->json('resultados')->nullable();
            $table->json('puntos_medicion')->nullable();
            $table->string('estado')->default('BORRADOR');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_ambientales');
    }
};
