<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'month',
        'year',
        'amount',
        'concept',
        'due_date',
        'status',
        'receipt_path', // <-- ¡Aquí está el campo agregado!
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}