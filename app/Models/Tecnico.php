<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $table = 'tecnicos';

    protected $fillable = [
        'nombre', 
        'apellido', 
        'ci',               
        'especialidad', 
        'telefono', 
        'email', 
        'activo', 
        'user_id'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialReparacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesores
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getTieneUsuarioAttribute()
    {
        return $this->user_id !== null;
    }

    public function getCiAttribute($value)
    {
        return $value;
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    public function scopeConUsuario($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeSinUsuario($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopePorEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }

    // Métodos de utilidad
    public function reparacionesPorEstado($estado)
    {
        return $this->reparaciones()->where('estado', $estado)->get();
    }

    public function getReparacionesEnProcesoAttribute()
    {
        return $this->reparaciones()->where('estado', '!=', 'entregado')->count();
    }

    public function getReparacionesCompletadasAttribute()
    {
        return $this->reparaciones()->where('estado', 'entregado')->count();
    }

    public function getReparacionesEnEvaluacionAttribute()
    {
        return $this->reparaciones()->where('estado', 'evaluacion')->count();
    }
}