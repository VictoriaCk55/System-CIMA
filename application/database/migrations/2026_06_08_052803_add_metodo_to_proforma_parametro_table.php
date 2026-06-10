<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proforma_parametro', function (Blueprint $table) {
            $table->string('metodo')->nullable()->after('precio_unitario');
        });
    }

    public function down(): void
    {
        Schema::table('proforma_parametro', function (Blueprint $table) {
            $table->dropColumn('metodo');
        });
    }
};
