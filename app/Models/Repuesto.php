<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'stock_actual',
        'stock_minimo', 'precio_unitario', 'proveedor'
    ];

    public function reparaciones()
    {
        return $this->belongsToMany(Reparacion::class, 'reparacion_repuestos')
                    ->withPivot('cantidad', 'precio_unitario', 'pedido_realizado', 'fecha_pedido', 'fecha_recepcion')
                    ->withTimestamps();
    }

    public function retirarStock($cantidad)
    {
        if ($this->stock_actual >= $cantidad) {
            $this->stock_actual -= $cantidad;
            $this->save();
            return true;
        }
        return false;
    }

    public function agregarStock($cantidad)
    {
        $this->stock_actual += $cantidad;
        $this->save();
    }

    public function necesitaPedido()
    {
        return $this->stock_actual <= $this->stock_minimo;
    }
}