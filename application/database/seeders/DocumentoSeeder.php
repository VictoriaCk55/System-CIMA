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
                'nombre' => 'Proforma de tipo Agua',
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
                    'nota1' => 'Para realizar el análisis se debe dejar cancelado el 100% del monto total.',
                    'nota2' => 'El laboratorio no realiza declaraciones de conformidad sobre los resultados que se reportan.',
                    'nota3' => 'Los resultados estarán disponibles dentro de los plazos establecidos según el tipo de análisis.',
                ],
            ],
            [
                'slug' => 'solicitud-ensayo-ambiental',
                'nombre' => 'Proforma de tipo Ambiental',
                'codigo_documento' => 'PO01-FR02',
                'version' => '01',
                'fecha_documento' => '2025-01-01',
                'activo' => true,
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
                    'nota1' => 'Para realizar el análisis se debe dejar cancelado el 100% del monto total.',
                    'nota2' => 'El laboratorio no realiza declaraciones de conformidad sobre los resultados que se reportan.',
                    'nota3' => 'Los resultados estarán disponibles dentro de los plazos establecidos según el tipo de análisis.',
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
                    'universidad_nombre' => 'UNIVERSIDAD AUTONOMA TOMAS FRIAS',
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
                    'nota1' => 'La información del presente informe corresponde a los resultados de ensayos en la muestra recepcionada.',
                    'nota2' => '"CIMA-UATF", NO asume ninguna responsabilidad sobre la información proporcionada por el cliente, que pueda afectar la validez de los resultados.',
                    'nota3' => '"CIMA-UATF", solo reconoce como válidos, informes de ensayo emitidos en soporte físico, con las firmas y sellos autorizados.',
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
                    'nota1' => 'Este informe es válido únicamente con las firmas correspondientes.',
                    'nota2' => 'Los resultados reportados corresponden exclusivamente a las muestras analizadas.',
                    'nota3' => 'Prohibida la reproducción parcial de este informe sin autorización del CIMA.',
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
