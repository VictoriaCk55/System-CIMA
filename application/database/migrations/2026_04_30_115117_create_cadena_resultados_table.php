<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cadena_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cadena_custodia_id')->constrained('cadenas_custodia')->onDelete('cascade');

            // Puede vincularse al parámetro existente o ser libre
            $table->foreignId('parametro_id')->nullable()->constrained('parametros')->nullOnDelete();
            $table->string('parametro_nombre'); // copia por si el parámetro se edita después

            // Resultados (Hoja 3)
            $table->string('metodo_ensayo')->nullable();
            $table->string('limite_cuantificacion')->nullable();
            $table->string('unidad')->nullable();
            $table->string('resultado')->nullable();
            $table->date('fecha_analisis')->nullable();
            $table->string('analizado_por')->nullable();
            $table->text('observaciones')->nullable();

            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cadena_resultados');
    }
};
