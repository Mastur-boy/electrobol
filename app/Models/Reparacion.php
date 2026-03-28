<?php
// app/Models/Reparacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparacion extends Model
{
    use HasFactory;
   
    public function getRouteKeyName()
    {
        return 'id';
    }

    
    protected $table = 'reparaciones'; 

    protected $fillable = [
        'articulo_id', 'tecnico_id', 'estado', 'diagnostico', 'solucion',
        'tiempo_estimado_horas', 'costo_reparacion', 'fecha_asignacion',
        'fecha_inicio_reparacion', 'fecha_fin_reparacion', 'fecha_entrega'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_inicio_reparacion' => 'date',
        'fecha_fin_reparacion' => 'date',
        'fecha_entrega' => 'date',
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }

    public function repuestos()
    {
        return $this->belongsToMany(Repuesto::class, 'reparacion_repuestos')
                    ->withPivot('cantidad', 'precio_unitario', 'pedido_realizado', 'fecha_pedido', 'fecha_recepcion')
                    ->withTimestamps();
    }

    public function historial()
    {
        return $this->hasMany(HistorialReparacion::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function registrarHistorial($accion, $detalle)
    {
        return $this->historial()->create([
            'tecnico_id' => $this->tecnico_id,
            'accion' => $accion,
            'detalle' => $detalle
        ]);
    }

    public function cambiarEstado($nuevoEstado)
    {
        $this->estado = $nuevoEstado;
        $this->save();
        
        $this->registrarHistorial('estado', "Estado cambiado a: {$nuevoEstado}");
    }
}