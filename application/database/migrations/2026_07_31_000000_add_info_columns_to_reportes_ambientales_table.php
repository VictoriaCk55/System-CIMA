<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->json('info_aire')->nullable();
            $table->json('info_gases')->nullable();
            $table->json('info_ruido')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn(['info_aire', 'info_gases', 'info_ruido']);
        });
    }
};
