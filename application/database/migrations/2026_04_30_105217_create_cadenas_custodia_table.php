<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cadenas_custodia', function (Blueprint $table) {
            $table->id();

            // Vinculación con el sistema existente
            $table->foreignId('proforma_id')->constrained('proformas')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();

            // Datos de recepción (Hoja 1)
            $table->string('numero_recepcion')->unique();
            $table->date('fecha_recepcion');
            $table->time('hora_recepcion')->nullable();

            // Datos de la muestra (Hoja 1)
            $table->string('tipo_muestra');
            $table->enum('muestreado_por', ['cliente', 'tecnico_cima', 'contratista']);
            $table->date('fecha_muestreo');
            $table->time('hora_muestreo');
            $table->string('procedencia');
            $table->string('codigo_cliente')->nullable();
            $table->string('coordenadas_n')->nullable();
            $table->string('coordenadas_e')->nullable();
            $table->string('codigo_laboratorio')->nullable();

            // Totales financieros (Hoja 1)
            $table->decimal('adelanto', 12, 2)->default(0);
            $table->decimal('saldo', 12, 2)->default(0);
            $table->text('observaciones_muestreo')->nullable();

            // Firmas (Hoja 1 y Hoja 4)
            $table->string('firma_laboratorio')->nullable();
            $table->string('firma_tecnico')->nullable();
            $table->string('firma_responsable')->nullable();

            // Datos del informe final (Hoja 4)
            $table->date('fecha_emision_informe')->nullable();
            $table->date('fecha_entrega_informe')->nullable();
            $table->text('conclusiones_informe')->nullable();
            $table->text('recomendaciones_informe')->nullable();

            // Control
            $table->enum('estado', ['borrador', 'completo', 'finalizado'])->default('borrador');
            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cadenas_custodia');
    }
};
