<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('limites_permisibles', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('parametro_nombre');
            $table->string('limite_diario')->nullable();
            $table->string('limite_mes')->nullable();
            $table->string('limite_permisible')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'parametro_nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('limites_permisibles');
    }
};
