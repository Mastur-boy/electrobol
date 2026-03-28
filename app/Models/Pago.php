<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reparacion_id', 'monto', 'tipo', 'metodo_pago', 'fecha_pago', 'comprobante'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class);
    }
}