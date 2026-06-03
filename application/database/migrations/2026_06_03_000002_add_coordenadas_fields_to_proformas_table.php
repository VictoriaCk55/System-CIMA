<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->string('zona_utm', 20)->nullable()->after('coordenadas');
            $table->string('punto_cardinal_1', 5)->nullable()->after('zona_utm');
            $table->string('valor_cardinal_1', 50)->nullable()->after('punto_cardinal_1');
            $table->string('punto_cardinal_2', 5)->nullable()->after('valor_cardinal_1');
            $table->string('valor_cardinal_2', 50)->nullable()->after('punto_cardinal_2');
        });
    }

    public function down(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->dropColumn(['zona_utm', 'punto_cardinal_1', 'valor_cardinal_1', 'punto_cardinal_2', 'valor_cardinal_2']);
        });
    }
};
