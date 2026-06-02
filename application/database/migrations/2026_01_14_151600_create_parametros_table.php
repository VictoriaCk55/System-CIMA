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
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('metodo');
            $table->string('codigo_poe')->nullable();
            $table->string('limite_cuantificacion')->nullable();
            $table->string('unidad')->nullable();
            $table->string('matriz')->nullable();
            $table->string('tecnica')->nullable();
            $table->decimal('precio_unitario', 12, 2);
            $table->enum('tipo', ['AMBIENTAL', 'AGUA', 'INVESTIGACION']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
