<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // Agregamos 'tower' para poder guardarlo
    protected $fillable = ['condominium_id', 'number', 'tower', 'type', 'prorata'];

    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }

    // Nueva relación: Una unidad tiene un residente responsable
    public function resident()
    {
        return $this->hasOne(Resident::class);
    }
}