<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proforma_resultado_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->constrained('proformas')->cascadeOnDelete();
            $table->foreignId('parametro_id')->nullable()->constrained('parametros')->nullOnDelete();
            $table->string('campo_modificado');
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->text('motivo')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('tipo')->default('modificacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_resultado_auditoria');
    }
};
