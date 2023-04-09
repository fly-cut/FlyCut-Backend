<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbershopAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'longitude',
        'latitude',
        'address'
    ];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}