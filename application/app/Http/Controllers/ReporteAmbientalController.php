<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use App\Models\Proforma;
use App\Models\ReporteAmbiental;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteAmbientalController extends Controller
{
    public function index(Proforma $proforma)
    {
        if ($proforma->tipo !== 'AMBIENTAL') {
            return redirect()->route('proformas.show', $proforma)
                ->with('error', 'El reporte ambiental solo está disponible para proformas de tipo AMBIENTAL.');
        }

        $reporte = ReporteAmbiental::where('proforma_id', $proforma->id)->first();

        $params = $proforma->parametros()
            ->where('tipo', 'AMBIENTAL')
            ->get();

        $categorias = $params->pluck('categoria')->unique()->values()->toArray();

        $conteo = $params->groupBy('categoria')->map->count()->toArray();

        return view('reportes.ambiental', compact('proforma', 'reporte', 'categorias', 'conteo'));
    }

    protected function cargarFormulario(Proforma $proforma, string $categoria, string $vista)
    {
        if ($proforma->tipo !== 'AMBIENTAL') {
            return redirect()->route('proformas.show', $proforma)
                ->with('error', 'El reporte ambiental solo está disponible para proformas de tipo AMBIENTAL.');
        }

        $categorias = $proforma->parametros()
            ->where('tipo', 'AMBIENTAL')
            ->get()
            ->pluck('categoria')
            ->unique()
            ->values()
            ->toArray();

        if (! in_array($categoria, $categorias)) {
            return redirect()->route('reportes.ambiental.index', $proforma)
                ->with('error', "La proforma no contiene parámetros de la categoría {$categoria}.");
        }

        $reporte = ReporteAmbiental::where('proforma_id', $proforma->id)->first() ?? new ReporteAmbiental(['proforma_id' => $proforma->id]);

        return view($vista, compact('proforma', 'reporte'));
    }

    public function aire(Proforma $proforma)
    {
        return $this->cargarFormulario($proforma, 'AIRE', 'reportes.aire');
    }

    public function ruido(Proforma $proforma)
    {
        return $this->cargarFormulario($proforma, 'RUIDO', 'reportes.ruido');
    }

    public function gases(Proforma $proforma)
    {
        return $this->cargarFormulario($proforma, 'GASES', 'reportes.gases');
    }

    public function store(Request $request, Proforma $proforma)
    {
        if ($proforma->tipo !== 'AMBIENTAL') {
            return redirect()->route('proformas.show', $proforma)
                ->with('error', 'El reporte ambiental solo está disponible para proformas de tipo AMBIENTAL.');
        }

        $categoria = $request->input('categoria', '');

        $reglas = [
            'codigo_reporte' => 'nullable|string|max:255',
            'fecha_emision' => 'nullable|date',
            'fecha_medicion' => 'nullable|date',
            'fecha_inicio_muestreo' => 'nullable|date',
            'fecha_fin_muestreo' => 'nullable|date',
            'periodo_medicion' => 'nullable|string|max:255',
            'tipo_muestreo' => 'nullable|string|max:100',
            'tipo_medicion' => 'nullable|string|max:100',
            'medicion_efectuada_por' => 'nullable|string|max:255',
            'equipo_usado' => 'nullable|string|max:255',
            'condiciones_muestreo' => 'nullable|string',
            'condiciones_reporte' => 'nullable|string',
            'comentarios' => 'nullable|string',
            'responsable_uia' => 'nullable|string|max:255',
            'cargo_responsable' => 'nullable|string|max:255',
            'directora_cima' => 'nullable|string|max:255',
            'cargo_directora' => 'nullable|string|max:255',
            'subtipo_ruido' => 'nullable|string|max:50',
            'puntos_medicion' => 'nullable|array',
            'puntos_medicion.*.codigo' => 'nullable|string|max:255',
            'puntos_medicion.*.descripcion' => 'nullable|string|max:500',
            'puntos_medicion.*.zona' => 'nullable|string|max:5',
            'puntos_medicion.*.direccion1' => 'nullable|string|max:2',
            'puntos_medicion.*.valor1' => 'nullable|numeric',
            'puntos_medicion.*.direccion2' => 'nullable|string|max:2',
            'puntos_medicion.*.valor2' => 'nullable|numeric',
        ];

        if ($categoria === 'AIRE' || ! $categoria) {
            $parametrosAire = $proforma->parametros()->where('categoria', 'AIRE')->get();
            $reglasAire = [
                'resultados_aire' => 'nullable|array',
                'resultados_aire.*.codigo' => 'nullable|string|max:255',
                'resultados_aire.*.periodo' => 'nullable|string|max:255',
                'resultados_unidades' => 'nullable|array',
                'observaciones_aire' => 'nullable|string',
            ];
            foreach ($parametrosAire as $p) {
                $reglasAire["resultados_aire.*.{$p->nombre}.valor"] = 'nullable|numeric';
            }
            $reglas = array_merge($reglas, $reglasAire);
        }

        if ($categoria === 'RUIDO' || ! $categoria) {
            $reglas = array_merge($reglas, [
                'resultados_ruido' => 'nullable|array',
                'resultados_ruido.*.codigo' => 'nullable|string|max:255',
                'resultados_ruido.*.hora_inicial' => 'nullable|string|max:10',
                'resultados_ruido.*.hora_final' => 'nullable|string|max:10',
                'resultados_ruido.*.tipo_ruido' => 'nullable|string|max:50',
                'resultados_ruido.*.lmax' => 'nullable|numeric',
                'resultados_ruido.*.lmin' => 'nullable|numeric',
                'resultados_ruido.*.leq' => 'nullable|numeric',
                'resultados_unidad_ruido' => 'nullable|array',
                'resultados_unidad_ruido.lmax' => 'nullable|string|max:10',
                'resultados_unidad_ruido.lmin' => 'nullable|string|max:10',
                'resultados_unidad_ruido.leq' => 'nullable|string|max:10',
                'observaciones_ruido' => 'nullable|string',
            ]);
        }

        if ($categoria === 'GASES' || ! $categoria) {
            $parametrosGases = $proforma->parametros()->where('categoria', 'GASES')->get();

            // Replicar el mismo filtro que usa la vista (gasesNombres desde pivot->metodo)
            $gasesMetodo = '';
            foreach ($parametrosGases as $pg) {
                if ($pg->pivot->metodo) {
                    $gasesMetodo = $pg->pivot->metodo;
                    break;
                }
            }
            $gasesNombres = $gasesMetodo ? array_map('trim', explode(',', $gasesMetodo)) : [];
            if (! empty($gasesNombres)) {
                $parametrosGases = Parametro::where('categoria', 'GASES')
                    ->whereIn('nombre', $gasesNombres)
                    ->get();
            }

            $reglasGases = [
                'resultados_gases' => 'nullable|array',
                'resultados_gases.*.codigo' => 'nullable|string|max:255',
                'resultados_gases.*.periodo' => 'nullable|string|max:255',
                'resultados_unidades' => 'nullable|array',
                'observaciones_gases' => 'nullable|string',
            ];
            foreach ($parametrosGases as $p) {
                $reglasGases["resultados_gases.*.{$p->nombre}.valor"] = 'nullable|numeric';
            }
            $reglas = array_merge($reglas, $reglasGases);
        }

        $data = $request->validate($reglas);

        // Las claves con punto (ej: PM2.5) no sobreviven la validación por notación de puntos
        if ($categoria === 'AIRE' && ! empty($data['resultados_aire'])) {
            $rawAire = $request->input('resultados_aire') ?? [];
            foreach ($rawAire as $ri => $row) {
                foreach ($row as $clave => $valor) {
                    if (is_array($valor) && str_contains($clave, '.')) {
                        $data['resultados_aire'][$ri][$clave] = $valor;
                    }
                }
            }
        }

        // distribute header-level unidades to each row's param data
        if ($categoria === 'GASES' && $request->has('resultados_unidades')) {
            $unidades = $request->input('resultados_unidades');
            if (! empty($data['resultados_gases'])) {
                foreach ($data['resultados_gases'] as &$row) {
                    foreach ($unidades as $paramNombre => $unidad) {
                        if (isset($row[$paramNombre])) {
                            $row[$paramNombre]['unidad'] = $unidad;
                        }
                    }
                }
                unset($row);
            }
        }

        if ($categoria === 'AIRE' && $request->has('resultados_unidades')) {
            $unidades = $request->input('resultados_unidades');
            if (! empty($data['resultados_aire'])) {
                foreach ($data['resultados_aire'] as &$row) {
                    foreach ($unidades as $paramNombre => $unidad) {
                        if (isset($row[$paramNombre])) {
                            $row[$paramNombre]['unidad'] = $unidad;
                        }
                    }
                }
                unset($row);
            }
        }

        if ($categoria === 'RUIDO') {
            $data['unidad_ruido'] = $request->input('resultados_unidad_ruido');
        }

        // tag each punto with its categoria so forms don't mix
        if ($request->has('puntos_medicion')) {
            $tagged = [];
            foreach ($request->input('puntos_medicion') as $pt) {
                $pt['categoria'] = $categoria;
                $tagged[] = $pt;
            }

            $existing = \DB::table('reportes_ambientales')->where('proforma_id', $proforma->id)->value('puntos_medicion');
            $prev = $existing ? (json_decode($existing, true) ?? []) : [];

            $keep = [];
            foreach ($prev as $old) {
                if (! isset($old['categoria']) || $old['categoria'] !== $categoria) {
                    $keep[] = $old;
                }
            }

            $data['puntos_medicion'] = array_values(array_merge($keep, $tagged));
        }

        $data['proforma_id'] = $proforma->id;
        $data['estado'] = $request->input('accion', 'BORRADOR') === 'publicar' ? 'PUBLICADO' : 'BORRADOR';
        unset($data['categoria']);

        // Información general independiente por categoría
        $camposInfo = [
            'codigo_reporte',
            'fecha_emision',
            'fecha_medicion',
            'fecha_inicio_muestreo',
            'fecha_fin_muestreo',
            'periodo_medicion',
            'tipo_muestreo',
            'tipo_medicion',
            'medicion_efectuada_por',
            'equipo_usado',
            'condiciones_muestreo',
            'condiciones_reporte',
            'subtipo_ruido',
        ];

        $info = [];
        foreach ($camposInfo as $campo) {
            if (array_key_exists($campo, $data)) {
                $info[$campo] = $data[$campo];
                unset($data[$campo]);
            }
        }

        $columnaInfo = 'info_'.strtolower($categoria);
        if (in_array($columnaInfo, ['info_aire', 'info_gases', 'info_ruido'])) {
            $data[$columnaInfo] = $info;
        }

        DB::beginTransaction();
        try {
            $reporte = ReporteAmbiental::updateOrCreate(
                ['proforma_id' => $proforma->id],
                $data
            );
            DB::commit();

            if ($request->input('accion') === 'pdf') {
                return redirect()->route('reportes.ambiental.pdf', $reporte);
            }

            $redirectRoute = match ($categoria) {
                'AIRE' => 'reportes.ambiental.aire',
                'RUIDO' => 'reportes.ambiental.ruido',
                'GASES' => 'reportes.ambiental.gases',
                default => 'reportes.ambiental.index',
            };

            return redirect()->route($redirectRoute, $proforma)
                ->with('success', 'Reporte ambiental guardado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Error al guardar: '.$e->getMessage());
        }
    }

    protected function renderPdfHtml(ReporteAmbiental $reporte): string
    {
        $reporte->load('proforma.cliente');

        $vistas = [
            'AIRE' => 'reportes.aire-pdf',
            'RUIDO' => 'reportes.ruido-pdf',
            'GASES' => 'reportes.gas-pdf',
        ];

        $html = '';
        $first = true;
        foreach ($vistas as $categoria => $vista) {
            if (in_array($categoria, $reporte->categoriasPresentes())) {
                if (! $first) {
                    $html .= '<div style="page-break-before: always;"></div>';
                }
                $html .= view($vista, ['reporte' => $reporte])->render();
                $first = false;
            }
        }

        return $html;
    }

    public function pdf(ReporteAmbiental $reporte)
    {
        $html = $this->renderPdfHtml($reporte);

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isPhpEnabled', true);

        return $pdf->stream("reporte-ambiental-{$reporte->proforma_id}.pdf");
    }

    protected function pdfCategoria(ReporteAmbiental $reporte, string $categoria, string $vista)
    {
        $reporte->load('proforma.cliente');

        if (! in_array($categoria, $reporte->categoriasPresentes())) {
            abort(404, "La categoría {$categoria} no está presente en este reporte.");
        }

        $html = view($vista, ['reporte' => $reporte])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isPhpEnabled', true);

        return $pdf->stream("reporte-{$categoria}-{$reporte->proforma_id}.pdf");
    }

    public function pdfAire(ReporteAmbiental $reporte)
    {
        return $this->pdfCategoria($reporte, 'AIRE', 'reportes.aire-pdf');
    }

    public function pdfRuido(ReporteAmbiental $reporte)
    {
        return $this->pdfCategoria($reporte, 'RUIDO', 'reportes.ruido-pdf');
    }

    public function pdfGases(ReporteAmbiental $reporte)
    {
        return $this->pdfCategoria($reporte, 'GASES', 'reportes.gas-pdf');
    }

    public function downloadPdf(ReporteAmbiental $reporte)
    {
        $html = $this->renderPdfHtml($reporte);

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isPhpEnabled', true);

        return $pdf->download("reporte-ambiental-{$reporte->proforma_id}.pdf");
    }
}
