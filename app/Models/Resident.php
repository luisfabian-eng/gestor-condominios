<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'name',
        'rut',
        'email',
        'phone'
    ];

    // Relación: Un residente pertenece a una unidad
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}