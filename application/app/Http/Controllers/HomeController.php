<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Parametro;
use App\Models\Proforma;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'clientes' => Cliente::count(),
            'parametros' => Parametro::count(),
            'proformas' => Proforma::count(),
            'total_bs' => Proforma::sum('total') ?? 0,
        ];

        return view('dashboard', compact('stats'));
    }
}
