<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Le decimos a Laravel qué campos se pueden llenar en el formulario
    protected $fillable = ['title', 'location', 'urgency', 'status'];
}