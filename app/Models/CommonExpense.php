<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonExpense extends Model
{
    use HasFactory;

    // Campos que permitimos llenar desde el formulario
    protected $fillable = [
        'unit_id',
        'month',
        'year',
        'amount',
        'status',
        'due_date'
    ];

    // Relación: Este gasto pertenece a una unidad
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}