<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquete;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales por estado
        $totalPaquetes   = Paquete::count();
        $totalEnviando   = Paquete::where('estado', 'ENVIANDO')->count();
        $totalDespacho   = Paquete::where('estado', 'DESPACHO')->count();
        $totalRecibido   = Paquete::where('estado', 'RECIBIDO')->count();
        $totalAlmacen    = Paquete::where('estado', 'ALMACEN')->count();
        $totalInventario = Paquete::where('estado', 'INVENTARIO')->count();

        // Datos para el gráfico de destino
        $destinos = Paquete::select('destino', DB::raw('count(*) as total'))
            ->groupBy('destino')
            ->get();
        // Convierte a dos arrays simples
        $destinoLabels = $destinos->pluck('destino')->all();
        $destinoTotals = $destinos->pluck('total')->all();

        return view('dashboard', compact(
            'totalPaquetes',
            'totalEnviando',
            'totalDespacho',
            'totalRecibido',
            'totalAlmacen',
            'totalInventario',
            'destinoLabels',
            'destinoTotals'
        ));
    }
}
