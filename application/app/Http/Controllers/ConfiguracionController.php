<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index(?Documento $documento = null)
    {
        $documentos = Documento::where('activo', true)->get();

        if ($documento === null) {
            $documento = $documentos->first();
        }

        return view('configuraciones.index', compact('documentos', 'documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        $validated = $request->validate([
            'codigo_documento' => 'nullable|string|max:255',
            'version' => 'nullable|string|max:255',
            'fecha_documento' => 'nullable|string|max:255',
            'institucion_nombre' => 'nullable|string|max:255',
            'universidad_nombre' => 'nullable|string|max:255',
            'institucion_sigla' => 'nullable|string|max:50',
            'laboratorio_nombre' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'footer_texto' => 'nullable|string|max:500',
            'footer_direccion' => 'nullable|string|max:255',
            'footer_telefono' => 'nullable|string|max:255',
            'footer_email' => 'nullable|email|max:255',
            'responsable_nombre' => 'nullable|string|max:255',
            'responsable_cargo' => 'nullable|string|max:255',
            'director_nombre' => 'nullable|string|max:255',
            'director_cargo' => 'nullable|string|max:255',
            'nota1' => 'nullable|string|max:500',
            'nota2' => 'nullable|string|max:500',
            'nota3' => 'nullable|string|max:500',
        ]);

        $config = $documento->config ?? [];

        foreach ($validated as $key => $value) {
            if (in_array($key, ['codigo_documento', 'version', 'fecha_documento'])) {
                $documento->$key = $value;
            } else {
                $config[$key] = $value;
            }
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048']);
            $path = $request->file('logo')->store('documentos', 'public');
            $config['logo_path'] = $path;
        }

        if ($request->hasFile('firma')) {
            $request->validate(['firma' => 'nullable|image|mimes:jpg,jpeg,png|max:2048']);
            $path = $request->file('firma')->store('documentos', 'public');
            $config['firma_path'] = $path;
        }

        $documento->config = $config;
        $documento->save();

        return redirect()->route('configuraciones.index', $documento->slug)
            ->with('success', "Configuración de «{$documento->nombre}» guardada exitosamente.");
    }
}
