<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Reparacion;
use App\Models\Repuesto;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReparacionController extends Controller
{
    public function index()
    {
        // Agregar técnicos para el filtro
        $reparaciones = Reparacion::with(['articulo.cliente', 'tecnico'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $tecnicos = Tecnico::where('activo', true)->get();
        
        return view('reparaciones.index', compact('reparaciones', 'tecnicos'));
    }

    // Método create
    public function create()
    {
        $articulos = Articulo::doesntHave('reparacion')->get();
        $tecnicos = Tecnico::where('activo', true)->get();
        
        return view('reparaciones.create', compact('articulos', 'tecnicos'));
    }

    //Método store
    public function store(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required|exists:articulos,id',
            'tecnico_id' => 'required|exists:tecnicos,id',
        ]);
        
        // Verificar que el artículo no tenga reparación
        $articulo = Articulo::find($request->articulo_id);
        if ($articulo->reparacion) {
            return back()->with('error', 'Este artículo ya tiene una reparación asignada.');
        }
        
        DB::transaction(function () use ($request, $articulo) {
            $reparacion = Reparacion::create([
                'articulo_id' => $request->articulo_id,
                'tecnico_id' => $request->tecnico_id,
                'fecha_asignacion' => now(),
                'estado' => 'evaluacion'
            ]);
            
            $reparacion->registrarHistorial('asignacion', 
                "Reparación creada y asignada al técnico: " . $reparacion->tecnico->nombre_completo
            );
        });
        
        return redirect()->route('reparaciones.index')
            ->with('success', 'Reparación creada correctamente.');
    }

    public function show(Reparacion $reparacione)
    {
        // Cargar relaciones
        $reparacione->load(['articulo.cliente', 'tecnico', 'repuestos', 'historial']);
        
        $repuestosDisponibles = Repuesto::where('stock_actual', '>', 0)->get();
        
        return view('reparaciones.show', compact('reparacione', 'repuestosDisponibles'));
    }

    public function evaluar(Request $request, Reparacion $reparacione)
    {
        // PRIMERO: Cargar la relación articulo
        $reparacione->load('articulo');
    
        $request->validate([
            'diagnostico' => 'required|string',
            'tiempo_estimado_horas' => 'required|numeric|min:0.5',
            'costo_reparacion' => 'required|numeric|min:0',
        ]);

        // SEGUNDO: Verificar que el artículo existe
         if (!$reparacione->articulo) {
            return back()->with('error', 'No se puede evaluar porque la reparación no tiene un artículo asociado.');
        }

        DB::transaction(function () use ($request, $reparacione) {
            $reparacione->update([
                'diagnostico' => $request->diagnostico,
                'tiempo_estimado_horas' => $request->tiempo_estimado_horas,
                'costo_reparacion' => $request->costo_reparacion,
            ]);

            // TERCERO: Actualizar el artículo
            $reparacione->articulo->update([
                'evaluacion_realizada' => true
            ]);

            $reparacione->registrarHistorial('evaluacion', 
                "Evaluación completada. Diagnóstico: {$request->diagnostico}. Tiempo estimado: {$request->tiempo_estimado_horas}h. Costo: {$request->costo_reparacion}Bs."
            );
        });

    return redirect()->route('reparaciones.show', $reparacione)
        ->with('success', 'Evaluación registrada correctamente. Consulte al cliente para aprobar la reparación.');
    }

    public function aceptarReparacion(Reparacion $reparacione)
    {
        DB::transaction(function () use ($reparacione) {
            $reparacione->articulo->update([
                'reparacion_aceptada' => true
            ]);

            $reparacione->update([
                'estado' => 'reparacion',
                'fecha_inicio_reparacion' => now()
            ]);

            $reparacione->registrarHistorial('inicio_reparacion', 
                "Cliente aceptó la reparación. Inicio de trabajos."
            );
        });

        return redirect()->route('reparaciones.show', $reparacione)
            ->with('success', 'Reparación aceptada por el cliente. Trabajos iniciados.');
    }

    public function rechazarReparacion(Reparacion $reparacione)
    {
        DB::transaction(function () use ($reparacione) {
            $reparacione->articulo->update([
                'reparacion_aceptada' => false
            ]);

            $reparacione->pagos()->create([
                'monto' => 1008,
                'tipo' => 'evaluacion',
                'metodo_pago' => 'efectivo',
                'fecha_pago' => now()
            ]);

            $reparacione->registrarHistorial('finalizacion', 
                "Cliente rechazó la reparación. Se debe cobrar 1008s por evaluación."
            );
        });

        return redirect()->route('reparaciones.index')
            ->with('info', 'Reparación rechazada por el cliente. Genere la factura de evaluación.');
    }

    public function actualizarAvance(Request $request, Reparacion $reparacione)
    {
        $request->validate([
            'detalle_avance' => 'required|string',
        ]);

        $reparacione->registrarHistorial('avance', $request->detalle_avance);

        return redirect()->route('reparaciones.show', $reparacione)
            ->with('success', 'Avance registrado correctamente.');
    }

    public function esperarRepuesto(Request $request, Reparacion $reparacione)
    {
        $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'motivo' => 'required|string',
        ]);

        $reparacione->registrarHistorial('espera_repuesto', 
            "Esperando repuesto: {$request->motivo}"
        );

        return redirect()->route('reparaciones.show', $reparacione)
            ->with('info', 'Reparación en espera de repuesto.');
    }

    // MÉTODO FINALIZAR CON VALIDACIÓN DE REPUESTOS EN ESPERA
    public function finalizar(Request $request, Reparacion $reparacione)
    {
        $request->validate([
            'solucion' => 'required|string|min:10',
        ]);

        // Verificar si hay repuestos en espera no retirados
        $repuestosEnEspera = $reparacione->historial()
            ->where('accion', 'espera_repuesto')
            ->whereDoesntHave('reparacion.repuestos')
            ->exists();
        
        if ($repuestosEnEspera) {
            return back()->with('error', 'Debe retirar los repuestos desde Gestión de Repuestos antes de finalizar.');
        }

        DB::transaction(function () use ($request, $reparacione) {
            $reparacione->update([
                'estado' => 'entregado',
                'fecha_fin_reparacion' => now(),
                'solucion' => $request->solucion
            ]);

            $reparacione->registrarHistorial('finalizacion', 
                "Reparación finalizada. Solución: {$request->solucion}"
            );
        });

        return redirect()->route('reparaciones.show', $reparacione)
            ->with('success', 'Reparación finalizada. Notifique al cliente para la entrega.');
    }

    // MÉTODO ENTREGAR CON CÁLCULO TOTAL DE REPUESTOS
    public function entregar(Request $request, Reparacion $reparacione)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,qr',
        ]);

        DB::transaction(function () use ($request, $reparacione) {
            // Calcular total (costo reparación + repuestos)
            $total = $reparacione->costo_reparacion ?? 0;
            
            foreach ($reparacione->repuestos as $repuesto) {
                $total += $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario;
            }
            
            // Registrar pago
            $reparacione->pagos()->create([
                'monto' => $total,
                'tipo' => 'reparacion',
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => now()
            ]);

            $reparacione->update([
                'fecha_entrega' => now()
            ]);

            $reparacione->registrarHistorial('entrega', 
                "Equipo entregado al cliente. Pago: Bs {$total} - Método: " . strtoupper($request->metodo_pago)
            );
        });

        return redirect()->route('reparaciones.show', $reparacione)
            ->with('success', 'Equipo entregado correctamente. Factura generada.');
    }

    public function generarInforme(Reparacion $reparacione)
    {
        // Cargar relaciones para el informe
        $reparacione->load(['articulo.cliente', 'tecnico', 'repuestos']);
        return view('reparaciones.informe', compact('reparacione'));
    }

    public function generarFactura(Reparacion $reparacione)
    {
        // Cargar relaciones para la factura
        $reparacione->load(['articulo.cliente', 'tecnico', 'repuestos']);
        return view('reparaciones.factura', compact('reparacione'));
    }
}