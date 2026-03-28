<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'email', 'direccion'
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Scopes
    public function scopeConArticulos($query)
    {
        return $query->has('articulos');
    }

    public function scopeSinArticulos($query)
    {
        return $query->doesntHave('articulos');
    }
}