<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();

            // 1. CÓDIGO SEGÚN FORMATO CIMA
            $table->string('codigo')->unique()->nullable();

            // 2. RELACIONES
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // 3. TIPOS Y CLASIFICACIÓN
            $table->enum('tipo', ['AMBIENTAL', 'AGUA', 'INVESTIGACION'])->default('AMBIENTAL');
            $table->string('tipo_muestra')->nullable();

            // 4. RECEPCIÓN Y NÚMEROS
            $table->string('numero_recepcion')->nullable();
            $table->date('fecha_recepcion')->nullable();

            // 5. DATOS DE MUESTREO
            $table->date('fecha_muestreo')->nullable();
            $table->time('hora_muestreo')->nullable();
            $table->string('procedencia')->nullable();
            $table->string('codigo_cliente')->nullable();
            $table->string('coordenadas')->nullable();
            $table->string('muestreado_por')->nullable();

            // 6. CONTACTO
            $table->string('persona_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();

            // 7. FECHAS
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_programada')->nullable();

            // 8. CÁLCULOS ECONÓMICOS
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('adelanto', 12, 2)->default(0);
            $table->decimal('saldo', 12, 2)->default(0);

            // 9. DESCUENTO
            $table->boolean('aplica_descuento_institucional')->default(false);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0);

            // 10. OBSERVACIONES
            $table->text('observaciones')->nullable();
            $table->text('observaciones_adicionales')->nullable();

            // 11. ESTADO
            $table->enum('estado', ['BORRADOR', 'ENVIADA', 'APROBADA', 'RECHAZADA', 'FINALIZADA'])->default('BORRADOR');

            // 12. CADENA DE CUSTODIA
            $table->boolean('tiene_cadena_custodia')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
