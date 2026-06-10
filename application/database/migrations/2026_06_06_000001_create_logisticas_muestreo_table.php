<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logisticas_muestreo', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->string('descripcion');
            $table->decimal('costo', 10, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logisticas_muestreo');
    }
};
