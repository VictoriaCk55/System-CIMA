<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'razon_social' => 'MINA ESPERANZA',
                'persona_contacto' => 'ING JORGE LUIS MAMANI CONDORI',
                'telefono' => '72377218',
                'nit' => '123456789',
                'direccion' => 'TUPIZA - TOROPALCA',
            ],
            [
                'razon_social' => 'EMPRESA MINERA METALURGICA KARACHIPAMPA',
                'persona_contacto' => 'ING. IVAN PUCH',
                'telefono' => '72345678',
                'nit' => '987654321',
                'direccion' => 'POTOSÍ',
            ],
            [
                'razon_social' => 'GRISELDA JAMES ARCE',
                'persona_contacto' => 'UNIV. GRISELDA JAMES ARCE',
                'telefono' => '70123456',
                'nit' => '456789123',
                'direccion' => 'MUNICIPIO DE YOCALLA - SANTA LUCÍA',
            ],
            [
                'razon_social' => 'GRUPO I - TECNOLOGÍA QUÍMICA',
                'persona_contacto' => 'UNIV. FREDDY CONDORI',
                'telefono' => '71234567',
                'nit' => '321654987',
                'direccion' => 'ASANGARO',
            ],
            [
                'razon_social' => 'DECANATURA FACULTAD DE INGENIERIA MINERA',
                'persona_contacto' => 'ING. EPIFANIO MAMANI',
                'telefono' => '6229711',
                'nit' => '789123456',
                'direccion' => 'UNIVERSIDAD AUTÓNOMA "TOMÁS FRÍAS"',
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::firstOrCreate(
                ['razon_social' => $cliente['razon_social']], // Buscar por razón social
                $cliente // Si no existe, crear
            );
        }

        $this->command->info('✅ Clientes verificados/creados correctamente');
    }
}
