<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'condominium_id',
        'title',
        'content',
        'priority',
        'attachment_path',
        'is_active',
    ];

    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }
}
