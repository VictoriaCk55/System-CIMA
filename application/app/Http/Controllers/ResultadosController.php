<?php

namespace App\Http\Controllers;

use App\Models\CadenaResultado;
use App\Models\LimitePermisible;
use App\Models\Proforma;
use App\Models\ProformaResultadoAuditoria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            $esCreacion = CadenaResultado::where('proforma_id', $id)->count() === 0;

            // Eliminar resultados anteriores
            CadenaResultado::where('proforma_id', $id)->delete();

            $resultados = json_decode($request->resultados, true) ?? [];
            $responsables = json_decode($request->responsables, true) ?? [];
            $fechas = json_decode($request->fechas, true) ?? [];
            $vbs = json_decode($request->vbs, true) ?? [];

            // Guardar valores anteriores de proforma para auditoría
            $valoresAnteriores = [];
            if (! $esCreacion) {
                $camposGenerales = [
                    'fecha_inicio_ensayo', 'fecha_conclusion_ensayo', 'zona_utm',
                    'punto_cardinal_1', 'valor_cardinal_1', 'punto_cardinal_2',
                    'valor_cardinal_2', 'numero_recepcion',
                ];
                foreach ($camposGenerales as $c) {
                    $valoresAnteriores[$c] = $proforma->$c ?? '';
                }
            }

            $proforma->fecha_inicio_ensayo = $request->fecha_inicio_ensayo ?? $proforma->fecha_inicio_ensayo;
            $proforma->fecha_conclusion_ensayo = $request->fecha_conclusion_ensayo ?? $proforma->fecha_conclusion_ensayo;
            $proforma->zona_utm = $request->has('zona_utm') ? ($request->zona_utm ?: null) : $proforma->zona_utm;
            $proforma->punto_cardinal_1 = $request->punto_cardinal_1 ?? $proforma->punto_cardinal_1;
            $proforma->valor_cardinal_1 = $request->valor_cardinal_1 ?? $proforma->valor_cardinal_1;
            $proforma->punto_cardinal_2 = $request->punto_cardinal_2 ?? $proforma->punto_cardinal_2;
            $proforma->valor_cardinal_2 = $request->valor_cardinal_2 ?? $proforma->valor_cardinal_2;
            $proforma->numero_recepcion = $request->numero_recepcion ?? $proforma->numero_recepcion;
            $proforma->save();

            // Crear mapa de parámetros
            $parametrosMap = [];

            foreach ($proforma->parametros as $p) {
                $parametrosMap[$p->id] = $p;
            }

            foreach ($resultados as $muestra => $parametrosValores) {

                foreach ($parametrosValores as $parametroId => $valor) {

                    if ($valor === null || $valor === '') {
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

            // Registrar auditoría
            if ($esCreacion) {
                ProformaResultadoAuditoria::create([
                    'proforma_id' => $id,
                    'parametro_id' => null,
                    'campo_modificado' => 'resultados_completos',
                    'valor_anterior' => null,
                    'valor_nuevo' => 'Datos iniciales registrados',
                    'motivo' => 'Registro inicial de resultados',
                    'user_id' => Auth::id(),
                    'tipo' => 'creacion',
                ]);
            } else {
                // Modificación general vía guardado masivo (old system compat)
                foreach ($valoresAnteriores as $campo => $vAnterior) {
                    $vNuevo = $request->$campo ?? '';
                    if ((string) $vAnterior !== (string) $vNuevo) {
                        ProformaResultadoAuditoria::create([
                            'proforma_id' => $id,
                            'parametro_id' => null,
                            'campo_modificado' => $campo,
                            'valor_anterior' => $vAnterior,
                            'valor_nuevo' => $vNuevo,
                            'motivo' => $request->motivo ?? 'Modificación masiva',
                            'user_id' => Auth::id(),
                            'tipo' => 'modificacion',
                        ]);
                    }
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
                || ($proforma && ($proforma->fecha_inicio_ensayo || $proforma->fecha_conclusion_ensayo || $proforma->zona_utm));

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
                'numero_recepcion' => $proforma->numero_recepcion ?? '',
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
     * Modificar datos generales con trazabilidad
     */
    public function modificarDatosGenerales(Request $request, $id)
    {
        $request->validate([
            'campo' => 'required|string',
            'valor_nuevo' => 'nullable|string',
            'motivo' => 'required|string|min:5',
        ]);

        DB::beginTransaction();
        try {
            $proforma = Proforma::findOrFail($id);
            $campo = $request->campo;
            $valorAnterior = $proforma->$campo ?? '';

            if ($campo === 'fecha_inicio_ensayo' || $campo === 'fecha_conclusion_ensayo') {
                $proforma->$campo = $request->valor_nuevo ?: null;
            } else {
                $proforma->$campo = $request->valor_nuevo;
            }
            $proforma->save();

            ProformaResultadoAuditoria::create([
                'proforma_id' => $id,
                'parametro_id' => null,
                'campo_modificado' => $campo,
                'valor_anterior' => $valorAnterior,
                'valor_nuevo' => $request->valor_nuevo,
                'motivo' => $request->motivo,
                'user_id' => Auth::id(),
                'tipo' => 'modificacion',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Datos generales modificados correctamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al modificar datos generales',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Modificar un parámetro específico con trazabilidad
     */
    public function modificarParametro(Request $request, $id)
    {
        $request->validate([
            'parametro_id' => 'required|integer|exists:parametros,id',
            'campo' => 'required|string|in:resultado,responsable,fecha,vb',
            'valor_nuevo' => 'nullable|string',
            'motivo' => 'required|string|min:5',
            'muestra' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $proforma = Proforma::findOrFail($id);
            $parametroId = $request->parametro_id;
            $campo = $request->campo;
            $valorNuevo = $request->valor_nuevo;
            $muestra = $request->muestra;

            if ($campo === 'resultado') {
                $registro = CadenaResultado::where('proforma_id', $id)
                    ->where('parametro_id', $parametroId)
                    ->where('orden', $muestra)
                    ->first();

                if (! $registro) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró el resultado para este parámetro y muestra',
                    ], 404);
                }

                $valorAnterior = $registro->resultado;
                $registro->resultado = $valorNuevo;
                $registro->save();
            } else {
                $columnaMap = [
                    'responsable' => 'analizado_por',
                    'fecha' => 'fecha_analisis',
                    'vb' => 'vb',
                ];
                $columna = $columnaMap[$campo];

                $registros = CadenaResultado::where('proforma_id', $id)
                    ->where('parametro_id', $parametroId)
                    ->get();

                if ($registros->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron registros para este parámetro',
                    ], 404);
                }

                $valorAnterior = $registros->first()->$columna ?? '';

                foreach ($registros as $reg) {
                    $reg->$columna = $campo === 'fecha' ? ($valorNuevo ?: null) : $valorNuevo;
                    $reg->save();
                }
            }

            ProformaResultadoAuditoria::create([
                'proforma_id' => $id,
                'parametro_id' => $parametroId,
                'campo_modificado' => $campo.($muestra ? "_muestra_{$muestra}" : ''),
                'valor_anterior' => $valorAnterior ?? '',
                'valor_nuevo' => $valorNuevo,
                'motivo' => $request->motivo,
                'user_id' => Auth::id(),
                'tipo' => 'modificacion',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Parámetro modificado correctamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al modificar parámetro',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener historial de auditoría
     */
    public function historial($id, $parametroId = null)
    {
        try {
            $query = ProformaResultadoAuditoria::with('usuario:id,name')
                ->where('proforma_id', $id)
                ->orderBy('created_at', 'desc');

            if ($parametroId) {
                $query->where('parametro_id', $parametroId);
            }

            $registros = $query->get()->map(function ($r) {
                return [
                    'id' => $r->id,
                    'parametro_id' => $r->parametro_id,
                    'campo_modificado' => $r->campo_modificado,
                    'valor_anterior' => $r->valor_anterior,
                    'valor_nuevo' => $r->valor_nuevo,
                    'motivo' => $r->motivo,
                    'usuario' => $r->usuario->name ?? 'Sistema',
                    'fecha' => $r->created_at->format('d/m/Y H:i'),
                    'tipo' => $r->tipo,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $registros,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar historial',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Guardar todos los datos generales a la vez (edición inline)
     */
    public function guardarTodosGenerales(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            $proforma = Proforma::findOrFail($id);
            $campos = [
                'fecha_inicio_ensayo', 'fecha_conclusion_ensayo', 'numero_recepcion',
                'zona_utm', 'punto_cardinal_1', 'valor_cardinal_1',
                'punto_cardinal_2', 'valor_cardinal_2',
            ];

            foreach ($campos as $campo) {
                $vAnterior = $proforma->$campo ?? '';
                $vNuevo = $request->$campo ?? '';

                if ((string) $vAnterior !== (string) $vNuevo) {
                    if (in_array($campo, ['fecha_inicio_ensayo', 'fecha_conclusion_ensayo'])) {
                        $proforma->$campo = $vNuevo ?: null;
                    } else {
                        $proforma->$campo = $vNuevo;
                    }

                    ProformaResultadoAuditoria::create([
                        'proforma_id' => $id,
                        'parametro_id' => null,
                        'campo_modificado' => $campo,
                        'valor_anterior' => $vAnterior,
                        'valor_nuevo' => $vNuevo,
                        'motivo' => $request->motivo,
                        'user_id' => Auth::id(),
                        'tipo' => 'modificacion',
                    ]);
                }
            }

            $proforma->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Datos generales guardados correctamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar datos generales',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener el estado de bloqueo actual
     */
    public function estadoBloqueo($id)
    {
        try {
            $tieneDatos = CadenaResultado::where('proforma_id', $id)->exists()
                || ProformaResultadoAuditoria::where('proforma_id', $id)->exists();

            return response()->json([
                'success' => true,
                'bloqueado' => $tieneDatos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar estado',
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
