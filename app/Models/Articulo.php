<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla explícitamente
    protected $table = 'articulos';

    protected $fillable = [
        'cliente_id', 
        'tipo', 
        'marca', 
        'modelo', 
        'serial',
        'tiene_garantia', 
        'fecha_garantia', 
        'problema_descripcion',
        'evaluacion_realizada', 
        'reparacion_aceptada', 
        'costo_evaluacion'
    ];

    protected $casts = [
        'tiene_garantia' => 'boolean',
        'evaluacion_realizada' => 'boolean',
        'reparacion_aceptada' => 'boolean',
        'fecha_garantia' => 'date',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function reparacion()
    {
        return $this->hasOne(Reparacion::class);
    }

    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class);
    }

    // Métodos de utilidad
    public function tieneGarantiaVigente()
    {
        if (!$this->tiene_garantia || !$this->fecha_garantia) {
            return false;
        }
        return $this->fecha_garantia >= now();
    }

    public function getEstadoReparacionAttribute()
    {
        if (!$this->reparacion) {
            return 'Sin asignar';
        }
        
        $estados = [
            'evaluacion' => 'En Evaluación',
            'reparacion' => 'En Reparación',
            'entregado' => 'Entregado'
        ];
        
        return $estados[$this->reparacion->estado] ?? $this->reparacion->estado;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->tipo} {$this->marca} {$this->modelo}";
    }

    public function getTieneReparacionAttribute()
    {
        return $this->reparacion !== null;
    }

    // Scopes para consultas comunes
    public function scopeConGarantia($query)
    {
        return $query->where('tiene_garantia', true)
                     ->where('fecha_garantia', '>=', now());
    }

    public function scopeSinGarantia($query)
    {
        return $query->where(function($q) {
            $q->where('tiene_garantia', false)
              ->orWhereNull('fecha_garantia')
              ->orWhere('fecha_garantia', '<', now());
        });
    }

    public function scopeSinReparacion($query)
    {
        return $query->whereDoesntHave('reparacion');
    }

    public function scopeEnReparacion($query)
    {
        return $query->whereHas('reparacion', function($q) {
            $q->where('estado', '!=', 'entregado');
        });
    }

    // Accesor para mostrar información del cliente de forma segura
    public function getClienteNombreAttribute()
    {
        return $this->cliente ? $this->cliente->nombre_completo : 'Cliente no registrado';
    }

    public function getClienteTelefonoAttribute()
    {
        return $this->cliente ? $this->cliente->telefono : 'N/A';
    }

    public function getClienteEmailAttribute()
    {
        return $this->cliente ? $this->cliente->email : 'N/A';
    }
}