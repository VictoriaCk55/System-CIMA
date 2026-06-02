<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('configuraciones');

        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->string('codigo_documento')->nullable();
            $table->string('version')->nullable();
            $table->string('fecha_documento')->nullable();
            $table->json('config')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');

        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('institucion_nombre')->nullable();
            $table->string('laboratorio_nombre')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('footer_texto')->nullable();
            $table->string('footer_direccion')->nullable();
            $table->string('footer_telefono')->nullable();
            $table->string('footer_email')->nullable();
            $table->string('responsable_nombre')->nullable();
            $table->string('responsable_cargo')->nullable();
            $table->string('director_nombre')->nullable();
            $table->string('director_cargo')->nullable();
            $table->string('firma_path')->nullable();
            $table->timestamps();
        });
    }
};
