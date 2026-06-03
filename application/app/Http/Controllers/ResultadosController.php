<?php

namespace App\Http\Controllers;

use App\Models\CadenaResultado;
use App\Models\LimitePermisible;
use App\Models\Proforma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    /**
     * Mostrar vista de ingreso de resultados
     */
    public function index($id)
    {
        $proforma = Proforma::with('parametros', 'cliente')->findOrFail($id);

        // Cargar resultados guardados
        $resultadosGuardados = CadenaResultado::where('proforma_id', $id)
            ->orderBy('orden')
            ->get();

        $resultados = [];
        $responsables = [];
        $fechas = [];
        $vbs = [];

        foreach ($resultadosGuardados as $rg) {

            $muestra = $rg->orden ?? 1;

            $resultados[$muestra][$rg->parametro_id] = $rg->resultado;

            $responsables[$rg->parametro_id] = $rg->analizado_por;

            $fechas[$rg->parametro_id] = $rg->fecha_analisis
                ? date('Y-m-d', strtotime($rg->fecha_analisis))
                : null;

            $vbs[$rg->parametro_id] = $rg->vb ?? '';
        }

        return view(
            'proformas.ingreso-resultados',
            compact(
                'proforma',
                'resultados',
                'responsables',
                'fechas',
                'vbs'
            ) + [
                'fecha_inicio_ensayo' => $proforma->fecha_inicio_ensayo?->format('Y-m-d') ?? '',
                'fecha_conclusion_ensayo' => $proforma->fecha_conclusion_ensayo?->format('Y-m-d') ?? '',
            ]
        );
    }

    /**
     * Guardar resultados en BD
     */
    public function guardarResultados(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $proforma = Proforma::with('parametros')->findOrFail($id);

            // Eliminar resultados anteriores
            CadenaResultado::where('proforma_id', $id)->delete();

            $resultados = json_decode($request->resultados, true) ?? [];

            $responsables = json_decode($request->responsables, true) ?? [];

            $fechas = json_decode($request->fechas, true) ?? [];

            $vbs = json_decode($request->vbs, true) ?? [];

            $proforma->fecha_inicio_ensayo = $request->fecha_inicio_ensayo ?? $proforma->fecha_inicio_ensayo;
            $proforma->fecha_conclusion_ensayo = $request->fecha_conclusion_ensayo ?? $proforma->fecha_conclusion_ensayo;
            $proforma->zona_utm = $request->zona_utm ?? $proforma->zona_utm;
            $proforma->punto_cardinal_1 = $request->punto_cardinal_1 ?? $proforma->punto_cardinal_1;
            $proforma->valor_cardinal_1 = $request->valor_cardinal_1 ?? $proforma->valor_cardinal_1;
            $proforma->punto_cardinal_2 = $request->punto_cardinal_2 ?? $proforma->punto_cardinal_2;
            $proforma->valor_cardinal_2 = $request->valor_cardinal_2 ?? $proforma->valor_cardinal_2;
            $proforma->save();

            // Crear mapa de parámetros
            $parametrosMap = [];

            foreach ($proforma->parametros as $p) {
                $parametrosMap[$p->id] = $p;
            }

            foreach ($resultados as $muestra => $parametrosValores) {

                foreach ($parametrosValores as $parametroId => $valor) {

                    // Ignorar vacíos
                    if (
                        $valor === null ||
                        $valor === ''
                    ) {
                        continue;
                    }

                    $parametro = $parametrosMap[$parametroId] ?? null;

                    CadenaResultado::create([

                        'proforma_id' => $id,

                        'parametro_id' => $parametroId,

                        'parametro_nombre' => $parametro->nombre ?? '',

                        'metodo_ensayo' => $parametro->metodo ?? '',

                        'limite_cuantificacion' => $parametro->limite_cuantificacion ?? '',

                        'unidad' => $parametro->unidad ?? '',

                        'resultado' => $valor,

                        'fecha_analisis' => $fechas[$parametroId] ?? null,

                        'analizado_por' => $responsables[$parametroId] ?? null,

                        'vb' => $vbs[$parametroId] ?? null,

                        'observaciones' => null,

                        'orden' => $muestra,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resultados guardados correctamente',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar resultados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cargar resultados desde BD
     */
    public function cargarResultados($id)
    {
        try {

            $resultadosGuardados = CadenaResultado::where('proforma_id', $id)
                ->orderBy('orden')
                ->get();

            $resultados = [];
            $responsables = [];
            $fechas = [];
            $vbs = [];

            foreach ($resultadosGuardados as $rg) {

                $muestra = $rg->orden ?? 1;

                $resultados[$muestra][$rg->parametro_id] = $rg->resultado;

                $responsables[$rg->parametro_id] = $rg->analizado_por;

                $fechas[$rg->parametro_id] = $rg->fecha_analisis
                    ? date('Y-m-d', strtotime($rg->fecha_analisis))
                    : null;

                $vbs[$rg->parametro_id] = $rg->vb ?? '';
            }

            $proforma = Proforma::find($id);
            $tieneDatos = ! empty($resultados) || ! empty($responsables) || ! empty($fechas) || ! empty($vbs)
                || ($proforma && ($proforma->fecha_inicio_ensayo || $proforma->fecha_conclusion_ensayo));

            return response()->json([
                'success' => $tieneDatos,
                'resultados' => $resultados,
                'responsables' => $responsables,
                'fechas' => $fechas,
                'vbs' => $vbs,
                'fecha_inicio_ensayo' => $proforma?->fecha_inicio_ensayo?->format('Y-m-d') ?? '',
                'fecha_conclusion_ensayo' => $proforma?->fecha_conclusion_ensayo?->format('Y-m-d') ?? '',
                'zona_utm' => $proforma->zona_utm ?? '',
                'punto_cardinal_1' => $proforma->punto_cardinal_1 ?? '',
                'valor_cardinal_1' => $proforma->valor_cardinal_1 ?? '',
                'punto_cardinal_2' => $proforma->punto_cardinal_2 ?? '',
                'valor_cardinal_2' => $proforma->valor_cardinal_2 ?? '',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar resultados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Limpiar resultados de la BD
     */
    public function limpiarResultados($id)
    {
        DB::beginTransaction();
        try {
            CadenaResultado::where('proforma_id', $id)->delete();

            $proforma = Proforma::find($id);
            if ($proforma) {
                $proforma->fecha_inicio_ensayo = null;
                $proforma->fecha_conclusion_ensayo = null;
                $proforma->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resultados eliminados correctamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar resultados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generar PDF
     */
    public function generarPdfResultados($id)
    {
        $proforma = Proforma::with('parametros', 'cliente')
            ->findOrFail($id);

        // Cargar resultados desde BD
        $resultadosGuardados = CadenaResultado::where('proforma_id', $id)
            ->orderBy('orden')
            ->get();

        $resultados = [];
        $responsables = [];
        $fechas = [];
        $vbs = [];
        $limites = [];
        $unidades = [];

        foreach ($resultadosGuardados as $rg) {

            $muestra = $rg->orden ?? 1;

            $resultados[$muestra][$rg->parametro_id] = $rg->resultado;

            $responsables[$rg->parametro_id] = $rg->analizado_por;

            $fechas[$rg->parametro_id] = $rg->fecha_analisis
                ? date('Y-m-d', strtotime($rg->fecha_analisis))
                : null;

            $vbs[$rg->parametro_id] = $rg->vb ?? '';

            $limites[$rg->parametro_id] = $rg->limite_cuantificacion ?? '---';

            $unidades[$rg->parametro_id] = $rg->unidad ?? '---';
        }

        $pdf = Pdf::loadView(
            'proformas.resultados-pdf',
            compact(
                'proforma',
                'resultados',
                'responsables',
                'fechas',
                'vbs',
                'limites',
                'unidades'
            )
        );

        // Configuración PDF
        $pdf->setPaper('letter', 'landscape');

        return $pdf->stream(
            'resultados-ensayo-'.$proforma->codigo.'.pdf'
        );
    }

    public function imprimirResultados($id)
    {
        $proforma = Proforma::with('parametros', 'cliente')
            ->findOrFail($id);

        $resultadosGuardados = CadenaResultado::where('proforma_id', $id)
            ->orderBy('orden')
            ->get();

        $resultados = [];
        $responsables = [];
        $fechas = [];
        $vbs = [];

        foreach ($resultadosGuardados as $rg) {

            $muestra = $rg->orden ?? 1;

            $resultados[$muestra][$rg->parametro_id] = $rg->resultado;

            $responsables[$rg->parametro_id] = $rg->analizado_por;

            $fechas[$rg->parametro_id] = $rg->fecha_analisis
                ? date('Y-m-d', strtotime($rg->fecha_analisis))
                : null;

            $vbs[$rg->parametro_id] = $rg->vb ?? '';
        }

        $pdf = Pdf::loadView(
            'proformas.informe-resultados-pdf',
            compact(
                'proforma',
                'resultados',
                'responsables',
                'fechas',
                'vbs'
            ) + ['muestreo' => $proforma]
        );

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream(
            'imprimir-'.$proforma->codigo.'.pdf'
        );
    }

    public function imprimirResultadosPermisibles($id, $tipo = 'NB-512')
    {
        $proforma = Proforma::with('parametros', 'cliente')
            ->findOrFail($id);

        $resultadosGuardados = CadenaResultado::where('proforma_id', $id)
            ->orderBy('orden')
            ->get();

        $resultados = [];
        $responsables = [];
        $fechas = [];
        $vbs = [];

        foreach ($resultadosGuardados as $rg) {
            $muestra = $rg->orden ?? 1;
            $resultados[$muestra][$rg->parametro_id] = $rg->resultado;
            $responsables[$rg->parametro_id] = $rg->analizado_por;
            $fechas[$rg->parametro_id] = $rg->fecha_analisis
                ? date('Y-m-d', strtotime($rg->fecha_analisis))
                : null;
            $vbs[$rg->parametro_id] = $rg->vb ?? '';
        }

        $limitesPermisibles = LimitePermisible::where('tipo', $tipo)->get();
        $limitesMap = [];
        foreach ($limitesPermisibles as $lp) {
            $limitesMap[$lp->parametro_nombre] = $lp;
        }

        $pdf = Pdf::loadView(
            'proformas.informe-resultados-permisibles-pdf',
            compact(
                'proforma',
                'resultados',
                'responsables',
                'fechas',
                'vbs',
                'limitesMap',
                'tipo'
            ) + ['muestreo' => $proforma]
        );

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream(
            'informe-permisibles-'.$proforma->codigo.'.pdf'
        );
    }
}
