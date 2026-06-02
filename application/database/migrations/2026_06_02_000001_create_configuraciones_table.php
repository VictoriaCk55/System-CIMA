<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();

            // Cabecera
            $table->string('institucion_nombre')->nullable();
            $table->string('laboratorio_nombre')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();

            // Pie de página
            $table->text('footer_texto')->nullable();
            $table->string('footer_direccion')->nullable();
            $table->string('footer_telefono')->nullable();
            $table->string('footer_email')->nullable();

            // Firmas
            $table->string('responsable_nombre')->nullable();
            $table->string('responsable_cargo')->nullable();
            $table->string('director_nombre')->nullable();
            $table->string('director_cargo')->nullable();
            $table->string('firma_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
