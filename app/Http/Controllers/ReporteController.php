<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\Reparacion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function buscarPorTecnico(Request $request)
    {
        $tecnicos = Tecnico::where('activo', true)->get();
        $reparaciones = collect();

        if ($request->has('tecnico_id') && $request->tecnico_id) {
            $tecnico = Tecnico::find($request->tecnico_id);
            if ($tecnico) {
                $reparaciones = $tecnico->reparaciones()
                    ->with('articulo.cliente')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('reportes.buscar_tecnico', compact('tecnicos', 'reparaciones'));
    }

    public function historialTecnico(Request $request)
    {
        $tecnicos = Tecnico::where('activo', true)->get();
        $historial = collect();

        if ($request->has('tecnico_id') && $request->tecnico_id) {
            $tecnico = Tecnico::find($request->tecnico_id);
            if ($tecnico) {
                $historial = $tecnico->historial()
                    ->with('reparacion.articulo')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('reportes.historial_tecnico', compact('tecnicos', 'historial'));
    }

    public function dashboard()
    {
        // Estadísticas principales
        $totalReparaciones = Reparacion::count();
        $reparacionesEnProceso = Reparacion::where('estado', '!=', 'entregado')->count();
        $reparacionesEntregadas = Reparacion::where('estado', 'entregado')->count();
        $ingresosTotales = Pago::sum('monto');
        
        // Reparaciones agrupadas por estado
        $reparacionesPorEstado = Reparacion::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        // Top 5 técnicos con más reparaciones
        $reparacionesPorTecnico = Tecnico::withCount('reparaciones')
            ->orderBy('reparaciones_count', 'desc')
            ->take(5)
            ->get();

        // Datos adicionales útiles
        $reparacionesPorMes = Reparacion::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('count(*) as total')
            )
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalReparaciones',
            'reparacionesEnProceso',
            'reparacionesEntregadas',
            'ingresosTotales',
            'reparacionesPorEstado',
            'reparacionesPorTecnico',
            'reparacionesPorMes'
        ));
    }
}