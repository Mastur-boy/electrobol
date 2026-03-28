<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TecnicoController extends Controller
{
    public function index()
    {
        $tecnicos = Tecnico::with('user')->withCount('reparaciones')->orderBy('nombre')->get();
        return view('tecnicos.index', compact('tecnicos'));
    }

    public function create()
    {
        return view('tecnicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|unique:tecnicos,ci',
            'especialidad' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:tecnicos',
            'activo' => 'boolean',
        ]);

        Tecnico::create($request->all());

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico registrado correctamente.');
    }

    public function show(Tecnico $tecnico)
    {
        $tecnico->load('user');
        
        $reparaciones = $tecnico->reparaciones()
            ->with('articulo.cliente')
            ->orderBy('created_at', 'desc')
            ->get();

        $reparacionesEvaluacion = $reparaciones->where('estado', 'evaluacion');
        $reparacionesReparacion = $reparaciones->where('estado', 'reparacion');
        $reparacionesEntregado = $reparaciones->where('estado', 'entregado');

        $historial = $tecnico->historial()
            ->with('reparacion.articulo')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('tecnicos.show', compact(
            'tecnico', 
            'reparacionesEvaluacion', 
            'reparacionesReparacion', 
            'reparacionesEntregado', 
            'historial'
        ));
    }

    public function edit(Tecnico $tecnico)
    {
        return view('tecnicos.edit', compact('tecnico'));
    }

    public function update(Request $request, Tecnico $tecnico)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|unique:tecnicos,ci,' . $tecnico->id,
            'especialidad' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:tecnicos,email,' . $tecnico->id,
            'activo' => 'boolean',
        ]);

        $tecnico->update($request->all());

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico actualizado correctamente.');
    }

    public function destroy(Tecnico $tecnico)
    {
        if ($tecnico->reparaciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el técnico porque tiene reparaciones asignadas.');
        }

        $tecnico->delete();

        return redirect()->route('tecnicos.index')
            ->with('success', 'Técnico eliminado correctamente.');
    }

    public function reportePorTecnico(Tecnico $tecnico)
    {
        $reparaciones = $tecnico->reparaciones()
            ->with('articulo.cliente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tecnicos.reporte', compact('tecnico', 'reparaciones'));
    }

    public function reporteHistorial(Tecnico $tecnico)
    {
        $historial = $tecnico->historial()
            ->with('reparacion.articulo')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tecnicos.historial', compact('tecnico', 'historial'));
    }

    // ==================== MÉTODOS PARA VINCULAR USUARIO ====================

    public function vincularUsuarioForm(Tecnico $tecnico)
    {
        $usuarios = User::whereDoesntHave('tecnico')->get();
        return view('tecnicos.vincular', compact('tecnico', 'usuarios'));
    }

    public function vincularUsuario(Request $request, Tecnico $tecnico)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->tecnico && $user->tecnico->id != $tecnico->id) {
            return back()->with('error', 'Este usuario ya está vinculado al técnico: ' . $user->tecnico->nombre_completo);
        }

        $tecnico->user_id = $user->id;
        $tecnico->save();

        return redirect()->route('tecnicos.show', $tecnico)
            ->with('success', "Técnico vinculado con usuario: {$user->email}");
    }

    public function crearUsuarioYVincular(Request $request, Tecnico $tecnico)
    {
        $request->validate([
            'ci' => 'required|string|unique:users,ci',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request, $tecnico) {
            $user = User::create([
                'name' => $tecnico->nombre . ' ' . $tecnico->apellido,
                'ci' => $request->ci,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true
            ]);

            $tecnico->user_id = $user->id;
            $tecnico->save();
        });

        return redirect()->route('tecnicos.show', $tecnico)
            ->with('success', "✅ Usuario creado y vinculado correctamente.\n📧 Email: {$request->email}\n🆔 CI: {$request->ci}\n🔑 Contraseña: {$request->password}");
    }

    public function desvincularUsuario(Tecnico $tecnico)
    {
        $tecnico->user_id = null;
        $tecnico->save();

        return back()->with('success', 'Técnico desvinculado correctamente.');
    }
}