<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_financieros', function (Blueprint $table) {
            $table->id();

            // Relación polimórfica (puede venir de proforma, informe, etc.)
            $table->morphs('origen');

            // Cliente asociado
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');

            // Tipo de movimiento
            $table->enum('tipo', ['DEUDA', 'PAGO', 'AJUSTE']);

            // Monto del movimiento
            $table->decimal('monto', 12, 2);

            // Saldo del cliente DESPUÉS de este movimiento
            $table->decimal('saldo_cliente', 12, 2);

            // Descripción
            $table->string('concepto');

            // Fecha del movimiento
            $table->date('fecha');

            // Referencia (código de proforma, recibo, etc.)
            $table->string('referencia')->nullable();

            // Usuario que realizó la acción
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();

            // Observaciones adicionales
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['cliente_id', 'fecha']);
            $table->index('fecha');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_financieros');
    }
};
