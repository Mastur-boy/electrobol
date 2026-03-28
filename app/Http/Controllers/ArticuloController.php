<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\Tecnico;
use App\Models\Reparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index()
    {
        // Cargar cliente y reparación para evitar N+1
        $articulos = Articulo::with(['cliente', 'reparacion.tecnico'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('articulos.index', compact('articulos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('articulos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|string|max:100',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'nullable|string|max:100',
            'tiene_garantia' => 'boolean',
            'fecha_garantia' => 'nullable|date',
            'problema_descripcion' => 'required|string',
        ]);

        $articulo = Articulo::create($request->all());

        return redirect()->route('articulos.show', $articulo)
            ->with('success', 'Artículo registrado correctamente. Ahora debe asignarlo a un técnico.');
    }

    public function show(Articulo $articulo)
    {
        // Cargar relaciones necesarias
        $articulo->load(['cliente', 'reparacion.tecnico', 'reparacion.repuestos']);
        
        $tecnicos = Tecnico::where('activo', true)->orderBy('nombre')->get();
        
        return view('articulos.show', compact('articulo', 'tecnicos'));
    }

    public function edit(Articulo $articulo)
    {
        // Verificar si ya tiene reparación asignada
        if ($articulo->reparacion) {
            return redirect()->route('articulos.show', $articulo)
                ->with('warning', 'No se puede editar un artículo que ya tiene una reparación asignada.');
        }
        
        $clientes = Cliente::orderBy('nombre')->get();
        return view('articulos.edit', compact('articulo', 'clientes'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        // Verificar si ya tiene reparación asignada
        if ($articulo->reparacion) {
            return redirect()->route('articulos.show', $articulo)
                ->with('warning', 'No se puede editar un artículo que ya tiene una reparación asignada.');
        }
        
        $request->validate([
            'tipo' => 'required|string|max:100',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'nullable|string|max:100',
            'tiene_garantia' => 'boolean',
            'fecha_garantia' => 'nullable|date',
            'problema_descripcion' => 'required|string',
        ]);

        $articulo->update($request->all());

        return redirect()->route('articulos.show', $articulo)
            ->with('success', 'Artículo actualizado correctamente.');
    }

    public function asignarTecnico(Request $request, Articulo $articulo)
    {
        $request->validate([
            'tecnico_id' => 'required|exists:tecnicos,id',
        ]);

        // Verificar si ya tiene reparación
        if ($articulo->reparacion) {
            return redirect()->route('articulos.show', $articulo)
                ->with('error', 'Este artículo ya tiene una reparación asignada.');
        }

        DB::transaction(function () use ($request, $articulo) {
            // Crear la reparación
            $reparacion = Reparacion::create([
                'articulo_id' => $articulo->id,
                'tecnico_id' => $request->tecnico_id,
                'fecha_asignacion' => now(),
                'estado' => 'evaluacion'
            ]);

            // Registrar historial
            $reparacion->registrarHistorial('asignacion', 
                "Artículo asignado al técnico: " . $reparacion->tecnico->nombre_completo
            );
        });

        return redirect()->route('reparaciones.index')
            ->with('success', 'Artículo asignado correctamente al técnico.');
    }

    public function destroy(Articulo $articulo)
    {
        // Verificar si tiene reparación
        if ($articulo->reparacion) {
            return redirect()->route('articulos.index')
                ->with('error', 'No se puede eliminar un artículo que tiene una reparación asociada.');
        }
        
        $articulo->delete();

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo eliminado correctamente.');
    }
}