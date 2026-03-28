<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use App\Models\Reparacion;
use Illuminate\Http\Request;

class RepuestoController extends Controller
{
    public function index()
    {
        $repuestos = Repuesto::orderBy('nombre')->get();
        
        // 👈 CORREGIDO: Filtrar solo reparaciones activas y cargar relación articulo
        $reparaciones = Reparacion::where('estado', '!=', 'entregado')
            ->with(['articulo.cliente'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('repuestos.index', compact('repuestos', 'reparaciones'));
    }

    public function create()
    {
        return view('repuestos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:repuestos',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
        ]);

        Repuesto::create($request->all());

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto registrado correctamente.');
    }

    public function show(Repuesto $repuesto)
    {
        return view('repuestos.show', compact('repuesto'));
    }

    public function edit(Repuesto $repuesto)
    {
        return view('repuestos.edit', compact('repuesto'));
    }

    public function update(Request $request, Repuesto $repuesto)
    {
        $request->validate([
            'codigo' => 'required|string|unique:repuestos,codigo,' . $repuesto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
        ]);

        $repuesto->update($request->all());

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto actualizado correctamente.');
    }

    public function retirar(Request $request, Repuesto $repuesto)
    {
        $request->validate([
            'reparacion_id' => 'required|exists:reparaciones,id',
            'cantidad' => 'required|integer|min:1|max:' . $repuesto->stock_actual,
        ]);

        $reparacion = Reparacion::findOrFail($request->reparacion_id);

        if ($repuesto->stock_actual < $request->cantidad) {
            return back()->with('error', 'Stock insuficiente.');
        }

        // Reducir stock
        $repuesto->stock_actual -= $request->cantidad;
        $repuesto->save();

        // Registrar relación (tabla pivote)
        $reparacion->repuestos()->attach($repuesto->id, [
            'cantidad' => $request->cantidad,
            'precio_unitario' => $repuesto->precio_unitario,
            'pedido_realizado' => false
        ]);

        // Historial
        if (method_exists($reparacion, 'registrarHistorial')) {
            $reparacion->registrarHistorial(
                'avance',
                "Se retiró {$request->cantidad} unidad(es) de repuesto: {$repuesto->nombre}"
            );
        }

        return redirect()->route('reparaciones.show', $reparacion)
            ->with('success', 'Repuesto retirado correctamente.');
    }

    public function pedido(Request $request, Repuesto $repuesto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        return back()->with(
            'info',
            "Pedido de {$request->cantidad} unidades de {$repuesto->nombre} registrado."
        );
    }

    public function recibir(Request $request, Repuesto $repuesto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $repuesto->stock_actual += $request->cantidad;
        $repuesto->save();

        return back()->with(
            'success',
            "Se recibieron {$request->cantidad} unidades de {$repuesto->nombre}."
        );
    }

    public function destroy(Repuesto $repuesto)
    {
        $repuesto->delete();

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto eliminado correctamente.');
    }
}