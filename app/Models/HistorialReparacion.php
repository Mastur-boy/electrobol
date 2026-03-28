<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialReparacion extends Model
{
    use HasFactory;

    protected $table = 'historial_reparaciones';

    protected $fillable = [
        'reparacion_id', 'tecnico_id', 'accion', 'detalle'
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }
}