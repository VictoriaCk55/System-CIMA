<?php

namespace Database\Seeders;

use App\Models\Documento;
use Illuminate\Database\Seeder;

class DocumentoSeeder extends Seeder
{
    public function run(): void
    {
        $documentos = [
            [
                'slug' => 'solicitud-ensayo',
                'nombre' => 'FT-001 Solicitud de Ensayo',
                'codigo_documento' => 'PO01-FR02',
                'version' => '06',
                'fecha_documento' => '2025-01-01',
                'config' => [
                    'institucion_nombre' => 'Centro de Investigación Minero Ambiental (CIMA)',
                    'laboratorio_nombre' => 'CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL',
                    'direccion' => 'Av. Arce esq. Villazón s/n; Edificio Facultad de Ingeniería Minera Subsuelo',
                    'telefono' => 'Teléfono/Fax: 6229711',
                    'email' => 'cima@cima.edu.bo',
                    'footer_texto' => '* Por favor llame al CIMA antes de venir a recoger su informe, gracias.',
                    'footer_direccion' => 'Av. Arce esq. Villazón s/n; Edificio Facultad de Ingeniería Minera Subsuelo',
                    'footer_telefono' => 'Teléfono/Fax: 6229711',
                    'footer_email' => 'cima@cima.edu.bo',
                    'institucion_sigla' => 'CIMA',
                    'responsable_nombre' => 'Ing. ___________________________',
                    'responsable_cargo' => 'Responsable Técnico',
                    'director_nombre' => '___________________________',
                    'director_cargo' => 'Representante Legal / Cliente',
                ],
            ],
            [
                'slug' => 'cadena-custodia',
                'nombre' => 'PO04-FR01 Cadena de Custodia',
                'codigo_documento' => 'PO04-FR01',
                'version' => '05',
                'fecha_documento' => '2025-01-01',
                'config' => [
                    'institucion_nombre' => 'Centro de Investigación Minero Ambiental (CIMA)',
                    'laboratorio_nombre' => 'CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL',
                    'direccion' => 'Av. Arce esq. Villazón s/n',
                    'telefono' => 'Teléfono/Fax: 6229711',
                    'email' => 'cima@cima.edu.bo',
                    'institucion_sigla' => 'CIMA',
                ],
            ],
            [
                'slug' => 'resultados-ensayo',
                'nombre' => 'MTD1-FR06 Resultados de Ensayo',
                'codigo_documento' => 'MTD1-FR06',
                'version' => '05',
                'fecha_documento' => '2024-08-15',
                'config' => [
                    'institucion_nombre' => 'Centro de Investigación Minero Ambiental (CIMA)',
                    'laboratorio_nombre' => 'CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL',
                    'institucion_sigla' => 'CIMA',
                ],
            ],
            [
                'slug' => 'informe-resultados',
                'nombre' => 'IT-001 Informe de Resultados de Ensayo',
                'codigo_documento' => 'PO07-FR01',
                'version' => '08',
                'fecha_documento' => '2025-01-07',
                'config' => [
                    'institucion_nombre' => 'Centro de Investigación Minero Ambiental',
                    'laboratorio_nombre' => 'CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL',
                    'institucion_sigla' => 'CIMA-UATF',
                    'direccion' => 'Av. Arce esq. Villazon s/n Edificio Facultad de Ingeniería Minera Bloque 1',
                    'telefono' => 'Cel: 78570522',
                    'email' => 'cima-uatf@uatf.edu.bo',
                    'footer_direccion' => 'Av. Arce esq. Villazon s/n - Teléfono/Fax 62-29711',
                    'footer_telefono' => 'Cel: 78570522',
                    'footer_email' => 'cima-uatf@uatf.edu.bo',
                    'responsable_nombre' => 'Lic. Mayra Anghela Calderón Rosas',
                    'responsable_cargo' => 'RESPONSABLE - UAQ',
                    'director_nombre' => 'M.Sc. Ing. Elva Fernández I.',
                    'director_cargo' => 'DIRECTOR(A) CIMA - UATF',
                ],
            ],
            [
                'slug' => 'informe-final',
                'nombre' => 'IT-002 Informe Final',
                'codigo_documento' => 'INF-FR01',
                'version' => '01',
                'fecha_documento' => '2025-01-01',
                'config' => [
                    'institucion_nombre' => 'Centro de Investigación Minero Ambiental (CIMA)',
                    'laboratorio_nombre' => 'CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL',
                    'direccion' => 'Av. Arce esq. Villazón s/n',
                    'telefono' => 'Teléfono/Fax: 6229711',
                    'email' => 'cima@cima.edu.bo',
                    'footer_direccion' => 'Av. Arce esq. Villazón s/n',
                    'footer_telefono' => 'Teléfono/Fax: 6229711',
                    'footer_email' => 'cima@cima.edu.bo',
                    'institucion_sigla' => 'CIMA',
                    'responsable_nombre' => 'Responsable Técnico',
                    'responsable_cargo' => 'Centro de Investigación Minero Ambiental',
                    'director_nombre' => 'Director',
                    'director_cargo' => 'Centro de Investigación Minero Ambiental',
                ],
            ],
        ];

        foreach ($documentos as $data) {
            Documento::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
