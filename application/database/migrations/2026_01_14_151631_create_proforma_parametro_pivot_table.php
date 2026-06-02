<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proforma_parametro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->constrained()->onDelete('cascade');
            $table->foreignId('parametro_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad_muestras')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->storedAs('cantidad_muestras * precio_unitario');
            $table->timestamps();

            $table->unique(['proforma_id', 'parametro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_parametro');
    }
};
