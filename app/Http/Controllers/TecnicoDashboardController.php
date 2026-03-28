<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tecnico = $user->tecnico;
        
        if (!$tecnico) {
            return view('tecnicos.sin_asignacion');
        }
        
        $reparaciones = Reparacion::where('tecnico_id', $tecnico->id)
            ->with(['articulo.cliente'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $enEvaluacion = $reparaciones->where('estado', 'evaluacion');
        $enReparacion = $reparaciones->where('estado', 'reparacion');
        $entregadas = $reparaciones->where('estado', 'entregado');
        
        $totalAsignadas = $reparaciones->count();
        $totalEnProceso = $enEvaluacion->count() + $enReparacion->count();
        $totalCompletadas = $entregadas->count();
        
        return view('tecnicos.dashboard', compact(
            'tecnico',
            'enEvaluacion',
            'enReparacion',
            'entregadas',
            'totalAsignadas',
            'totalEnProceso',
            'totalCompletadas'
        ));
    }
    
    public function misReparaciones()
    {
        $user = Auth::user();
        $tecnico = $user->tecnico;
        
        if (!$tecnico) {
            return view('tecnicos.sin_asignacion');
        }       
      
        $reparaciones = Reparacion::where('tecnico_id', $tecnico->id)
            ->with(['articulo.cliente'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('tecnicos.reparaciones', compact('reparaciones', 'tecnico'));
    }
    
    public function evaluarForm(Reparacion $reparacione)
    {
        $user = Auth::user();
        $tecnico = $user->tecnico;
        
        if ($reparacione->tecnico_id != $tecnico->id) {
            abort(403, 'No tienes permiso para evaluar esta reparación.');
        }
        
        return view('tecnicos.evaluar', compact('reparacione'));
    }
    
    public function registrarAvance(Request $request, Reparacion $reparacione)
    {
        $request->validate([
            'detalle_avance' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $tecnico = $user->tecnico;
        
        if ($reparacione->tecnico_id != $tecnico->id) {
            abort(403, 'No tienes permiso para registrar avance en esta reparación.');
        }
        
        $reparacione->registrarHistorial('avance', $request->detalle_avance);
        
        return redirect()->route('tecnico.dashboard')
            ->with('success', 'Avance registrado correctamente.');
    }
}