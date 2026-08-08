<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condominium extends Model
{
    use HasFactory;

    // Le decimos a Laravel el nombre exacto de la tabla para anular el plural en inglés
    protected $table = 'condominiums';

    // Campos que permitimos guardar desde los formularios
    protected $fillable = [
        'name',
        'rut',
        'address',
        'city'
    ];

    // Relación: Un condominio tiene MUCHAS unidades
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}